<?php

namespace App\Controller\Gestion;

use App\Entity\Commande;
use App\Entity\CommandeStatusHistory;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYEE')]
#[Route('/gestion/commandes')]
final class CommandeController extends AbstractController
{
    private const STATUSES = [
        'en attente',
        'acceptée',
        'en préparation',
        'en cours de livraison',
        'livrée',
        'en attente du retour de matériel',
        'terminée',
        'annulée',
    ];

    #[Route(name: 'app_gestion_commande_index', methods: ['GET'])]
    public function index(Request $request, CommandeRepository $commandeRepository): Response
    {
        $status = $request->query->getString('status') ?: null;
        $customer = trim($request->query->getString('customer')) ?: null;

        if ($status && !in_array($status, self::STATUSES, true)) {
            $status = null;
        }

        return $this->render('gestion/commande/index.html.twig', [
            'commandes' => $commandeRepository->findForManagement($status, $customer),
            'statuses' => self::STATUSES,
            'currentStatus' => $status,
            'currentCustomer' => $customer,
        ]);
    }

    #[Route('/{id}/statut', name: 'app_gestion_commande_status', methods: ['POST'])]
    public function updateStatus(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $status = $request->request->getString('status');

        if (!$this->isCsrfTokenValid('status'.$commande->getId(), $request->request->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        if (!in_array($status, self::STATUSES, true)) {
            $this->addFlash('danger', 'Statut invalide.');

            return $this->redirectToRoute('app_gestion_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($commande->getStatus() !== $status) {
            $commande
                ->setStatus($status)
                ->addStatusHistory(
                    (new CommandeStatusHistory())
                        ->setStatus($status)
                        ->setChangedAt(new \DateTimeImmutable())
                )
            ;
        }

        $entityManager->flush();

        $this->addFlash('success', 'Le statut de la commande a bien été mis à jour.');

        return $this->redirectToRoute('app_gestion_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/annuler', name: 'app_gestion_commande_cancel', methods: ['POST'])]
    public function cancel(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('management_cancel'.$commande->getId(), $request->request->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $contact = $request->request->getString('contact');
        $reason = trim($request->request->getString('reason'));

        if (!in_array($contact, ['GSM', 'email'], true) || $reason === '') {
            $this->addFlash('danger', 'Le mode de contact et le motif d’annulation sont obligatoires.');

            return $this->redirectToRoute('app_gestion_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        $commande
            ->setStatus('annulée')
            ->setManagementCancellationContact($contact)
            ->setManagementCancellationReason($reason)
            ->addStatusHistory(
                (new CommandeStatusHistory())
                    ->setStatus('annulée')
                    ->setChangedAt(new \DateTimeImmutable())
            )
        ;

        $entityManager->flush();

        $this->addFlash('success', 'La commande a bien été annulée côté gestion.');

        return $this->redirectToRoute('app_gestion_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
