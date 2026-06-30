<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Menu;
use App\Entity\User;
use App\Form\CommandeEditType;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commandes')]
final class CommandeController extends AbstractController
{
    private const STATUS_PENDING = 'en attente';
    private const STATUS_CANCELLED = 'annulée';
    private const STATUS_FINISHED = 'terminée';

    #[Route(name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findForUser($user),
        ]);
    }

    #[Route('/nouvelle/{id}', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $commande = (new Commande())
            ->setMenu($menu)
            ->setUser($user)
            ->setDate(new \DateTimeImmutable())
            ->setStatus(self::STATUS_PENDING)
            ->setNbPers($menu->getMinPersons())
            ->setTotalPrice($menu->getPrice())
            ->setConditionsAccepted(false)
        ;

        $canOrder = $menu->getStockAvailable() >= $menu->getMinPersons();
        $form = $this->createForm(CommandeType::class, $commande, [
            'min_persons' => $menu->getMinPersons(),
            'stock_available' => $menu->getStockAvailable(),
        ]);
        $form->handleRequest($request);

        if ($canOrder && $form->isSubmitted() && $form->isValid()) {
            $commande->setTotalPrice($this->calculateTotalPrice($menu, $commande->getNbPers()));
            $menu->setStockAvailable($menu->getStockAvailable() - $commande->getNbPers());

            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commande a bien été enregistrée.');

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'menu' => $menu,
            'form' => $form,
            'canOrder' => $canOrder,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $this->denyAccessToOtherCustomerOrder($commande);
        $this->denyAccessUnlessPending($commande);

        $previousNbPers = $commande->getNbPers();
        $maxPersons = $commande->getMenu()->getStockAvailable() + $previousNbPers;

        $form = $this->createForm(CommandeEditType::class, $commande, [
            'min_persons' => $commande->getMenu()->getMinPersons(),
            'max_persons' => $maxPersons,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockDifference = $commande->getNbPers() - $previousNbPers;
            $commande->setTotalPrice($this->calculateTotalPrice($commande->getMenu(), $commande->getNbPers()));
            $commande->getMenu()->setStockAvailable($commande->getMenu()->getStockAvailable() - $stockDifference);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commande a bien été modifiée.');

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/annuler', name: 'app_commande_cancel', methods: ['POST'])]
    public function cancel(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $this->denyAccessToOtherCustomerOrder($commande);
        $this->denyAccessUnlessPending($commande);

        if ($this->isCsrfTokenValid('cancel'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $commande->setStatus(self::STATUS_CANCELLED);
            $commande->getMenu()->setStockAvailable($commande->getMenu()->getStockAvailable() + $commande->getNbPers());
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/masquer', name: 'app_commande_hide', methods: ['POST'])]
    public function hide(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $this->denyAccessToOtherCustomerOrder($commande);

        if (
            in_array($commande->getStatus(), [self::STATUS_CANCELLED, self::STATUS_FINISHED], true)
            && $this->isCsrfTokenValid('hide'.$commande->getId(), $request->getPayload()->getString('_token'))
        ) {
            $commande->setHiddenFromCustomer(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    private function calculateTotalPrice(Menu $menu, int $nbPers): string
    {
        $unitPrice = (float) $menu->getPrice() / $menu->getMinPersons();
        $totalPrice = $unitPrice * $nbPers;

        if ($nbPers >= $menu->getMinPersons() + 5) {
            $totalPrice *= 0.9;
        }

        return number_format($totalPrice, 2, '.', '');
    }

    private function denyAccessToOtherCustomerOrder(Commande $commande): void
    {
        if ($commande->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
    }

    private function denyAccessUnlessPending(Commande $commande): void
    {
        if ($commande->getStatus() !== self::STATUS_PENDING) {
            throw $this->createAccessDeniedException();
        }
    }
}
