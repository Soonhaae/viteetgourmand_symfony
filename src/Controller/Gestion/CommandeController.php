<?php

namespace App\Controller\Gestion;

use App\Entity\Commande;
use App\Entity\CommandeStatusHistory;
use App\Document\StatistiquesMenu;
use App\Repository\CommandeRepository;
use App\Repository\StatistiquesMenuRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
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
    public function updateStatus(Request $request, Commande $commande, EntityManagerInterface $entityManager, DocumentManager $dm): Response
    {
        $status = $request->request->getString('status');

        if (!$this->isCsrfTokenValid('status'.$commande->getId(), $request->request->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        if (!in_array($status, self::STATUSES, true)) {
            $this->addFlash('danger', 'Statut invalide.');

            return $this->redirectToRoute('app_gestion_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        $oldStatus = $commande->getStatus();
        if ($oldStatus !== $status) {
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

        if ($oldStatus !== $status) {
            /** @var StatistiquesMenuRepository $statsRepo */
            $statsRepo = $dm->getRepository(StatistiquesMenu::class);
            $mois      = (int) $commande->getDate()->format('n');
            $annee     = (int) $commande->getDate()->format('Y');
            $statDoc   = $statsRepo->findOneByMenuMois($commande->getMenu()->getId(), $mois, $annee);

            if ($status === 'annulée' && $statDoc !== null) {
                $statDoc->setNbCommandes(max(0, $statDoc->getNbCommandes() - 1));
                $statDoc->setChiffreAffaires(max(0.0, round($statDoc->getChiffreAffaires() - (float) $commande->getTotalPrice(), 2)));
                $dm->flush();
            } elseif ($oldStatus === 'annulée') {
                if ($statDoc === null) {
                    $statDoc = new StatistiquesMenu($commande->getMenu()->getId(), $commande->getMenu()->getTitle(), $commande->getDate());
                    $dm->persist($statDoc);
                }
                $statDoc->setNbCommandes($statDoc->getNbCommandes() + 1);
                $statDoc->setChiffreAffaires(round($statDoc->getChiffreAffaires() + (float) $commande->getTotalPrice(), 2));
                $dm->flush();
            }
        }

        $this->addFlash('success', 'Le statut de la commande a bien été mis à jour.');

        return $this->redirectToRoute('app_gestion_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/annuler', name: 'app_gestion_commande_cancel', methods: ['POST'])]
    public function cancel(Request $request, Commande $commande, EntityManagerInterface $entityManager, DocumentManager $dm): Response
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

        $dejaAnnulee = $commande->getStatus() === 'annulée';

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

        if (!$dejaAnnulee) {
            /** @var StatistiquesMenuRepository $statsRepo */
            $statsRepo = $dm->getRepository(StatistiquesMenu::class);
            $mois      = (int) $commande->getDate()->format('n');
            $annee     = (int) $commande->getDate()->format('Y');
            $statDoc   = $statsRepo->findOneByMenuMois($commande->getMenu()->getId(), $mois, $annee);
            if ($statDoc !== null) {
                $statDoc->setNbCommandes(max(0, $statDoc->getNbCommandes() - 1));
                $statDoc->setChiffreAffaires(max(0.0, round($statDoc->getChiffreAffaires() - (float) $commande->getTotalPrice(), 2)));
                $dm->flush();
            }
        }

        $this->addFlash('success', 'La commande a bien été annulée côté gestion.');

        return $this->redirectToRoute('app_gestion_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
