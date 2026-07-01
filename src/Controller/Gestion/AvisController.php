<?php

namespace App\Controller\Gestion;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYEE')]
#[Route('/gestion/avis')]
final class AvisController extends AbstractController
{
    #[Route(name: 'app_gestion_avis_index', methods: ['GET'])]
    public function index(AvisRepository $avisRepository): Response
    {
        return $this->render('gestion/avis/index.html.twig', [
            'avis' => $avisRepository->findForManagement(),
        ]);
    }

    #[Route('/{id}/valider', name: 'app_gestion_avis_validate', methods: ['POST'])]
    public function validateAvis(Request $request, Avis $avis, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('validate_avis'.$avis->getId(), $request->request->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $avis
            ->setValidated(true)
            ->setRefused(false)
            ->setPublished(false)
            ->setPublicExcerpt(null)
        ;

        $entityManager->flush();
        $this->addFlash('success', 'L’avis a bien été validé.');

        return $this->redirectToRoute('app_gestion_avis_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/publier', name: 'app_gestion_avis_publish', methods: ['POST'])]
    public function publishAvis(Request $request, Avis $avis, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('publish_avis'.$avis->getId(), $request->request->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $excerpt = trim($request->request->getString('publicExcerpt'));

        if ($excerpt === '') {
            $excerpt = mb_substr($avis->getComment(), 0, 255);
        }

        $avis
            ->setValidated(true)
            ->setRefused(false)
            ->setPublished(true)
            ->setPublicExcerpt(mb_substr($excerpt, 0, 255))
        ;

        $entityManager->flush();
        $this->addFlash('success', 'L’avis a bien été publié.');

        return $this->redirectToRoute('app_gestion_avis_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/refuser', name: 'app_gestion_avis_refuse', methods: ['POST'])]
    public function refuseAvis(Request $request, Avis $avis, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('refuse_avis'.$avis->getId(), $request->request->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $avis
            ->setValidated(false)
            ->setRefused(true)
            ->setPublished(false)
            ->setPublicExcerpt(null)
        ;

        $entityManager->flush();
        $this->addFlash('success', 'L’avis a bien été refusé.');

        return $this->redirectToRoute('app_gestion_avis_index', [], Response::HTTP_SEE_OTHER);
    }
}
