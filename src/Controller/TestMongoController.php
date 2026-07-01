<?php

namespace App\Controller;

use App\Document\TestDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/test-mongo')]
final class TestMongoController extends AbstractController
{
    #[Route('', name: 'app_test_mongo_index', methods: ['GET'])]
    public function index(DocumentManager $dm): Response
    {
        $documents = $dm->getRepository(TestDocument::class)->findAll();

        return $this->render('test_mongo/index.html.twig', [
            'documents' => $documents,
        ]);
    }

    #[Route('/new', name: 'app_test_mongo_new', methods: ['POST'])]
    public function new(Request $request, DocumentManager $dm): Response
    {
        $nom = $request->request->get('nom', '');
        $message = $request->request->get('message', '');

        if ($nom && $message) {
            $doc = new TestDocument($nom, $message);
            $dm->persist($doc);
            $dm->flush();
        }

        return $this->redirectToRoute('app_test_mongo_index');
    }
}
