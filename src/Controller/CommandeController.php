<?php

namespace App\Controller;

use App\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commandes')]
final class CommandeController extends AbstractController
{
    #[Route('/nouvelle/{id}', name: 'app_commande_new', methods: ['GET'])]
    public function new(Menu $menu): Response
    {
        return $this->render('commande/new.html.twig', [
            'menu' => $menu,
        ]);
    }
}
