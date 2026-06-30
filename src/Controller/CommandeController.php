<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Menu;
use App\Entity\User;
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
            ->setStatus('en attente')
            ->setNbPers($menu->getMinPersons())
            ->setTotalPrice($menu->getPrice())
            ->setConditionsAccepted(false)
        ;

        $canOrder = $menu->getStockAvailable() > 0;
        $form = $this->createForm(CommandeType::class, $commande, [
            'min_persons' => $menu->getMinPersons(),
        ]);
        $form->handleRequest($request);

        if ($canOrder && $form->isSubmitted() && $form->isValid()) {
            $commande->setTotalPrice($this->calculateTotalPrice($menu, $commande->getNbPers()));
            $menu->setStockAvailable($menu->getStockAvailable() - 1);

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

    private function calculateTotalPrice(Menu $menu, int $nbPers): string
    {
        $unitPrice = (float) $menu->getPrice() / $menu->getMinPersons();
        $totalPrice = $unitPrice * $nbPers;

        if ($nbPers >= $menu->getMinPersons() + 5) {
            $totalPrice *= 0.9;
        }

        return number_format($totalPrice, 2, '.', '');
    }
}
