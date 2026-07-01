<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
     public function index(AvisRepository $avisRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'publishedReviews' => $avisRepository->findPublishedForHomepage(),
        ]);
    }
}
