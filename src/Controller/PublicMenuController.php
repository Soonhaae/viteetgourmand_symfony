<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\RegimeRepository;
use Symfony\Component\Asset\Packages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublicMenuController extends AbstractController
{
    #[Route('/menus', name: 'app_public_menu_index', methods: ['GET'])]
    public function index(Request $request, MenuRepository $menuRepository, RegimeRepository $regimeRepository): Response
    {
        $filters = $this->getFilters($request);

        return $this->render('public_menu/index.html.twig', [
            'menus' => $menuRepository->findByFilters($filters),
            'filters' => $filters,
            'themes' => ['classique', 'evenement', 'noel', 'paques'],
            'regimes' => $regimeRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/menus/filter', name: 'app_public_menu_filter', methods: ['GET'])]
    public function filter(Request $request, MenuRepository $menuRepository, Packages $packages): JsonResponse
    {
        $filters = $this->getFilters($request);
        $menus = $menuRepository->findByFilters($filters);

        return $this->json([
            'menus' => array_map(fn (object $menu): array => $this->formatMenu($menu, $packages), $menus),
            'message' => count($menus) === 0 ? $this->buildEmptyMessage() : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function getFilters(Request $request): array
    {
        return [
            'theme' => $request->query->get('theme'),
            'regime' => $this->getNullableIntFilter($request, 'regime'),
            'minPersons' => $this->getNullableIntFilter($request, 'minPersons'),
            'minPrice' => $request->query->get('minPrice'),
            'maxPrice' => $request->query->get('maxPrice'),
            'availableOnly' => $request->query->getBoolean('availableOnly'),
            'publishedOnly' => true,
        ];
    }

    private function getNullableIntFilter(Request $request, string $name): ?int
    {
        $value = $request->query->get($name);

        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function formatMenu(object $menu, Packages $packages): array
    {
        $image = $menu->getImages()->first() ?: null;

        return [
            'orderUrl' => $this->generateUrl('app_commande_new', ['id' => $menu->getId()]),
            'title' => $menu->getTitle(),
            'theme' => $menu->getTheme(),
            'content' => $menu->getContent(),
            'minPersons' => $menu->getMinPersons(),
            'price' => $menu->getPrice(),
            'stockAvailable' => $menu->getStockAvailable(),
            'imageUrl' => $image ? $packages->getUrl($image->getUrl()) : null,
            'imageAlt' => $image ? $image->getAltText() : '',
        ];
    }

    private function buildEmptyMessage(): string
    {
        return 'Aucun menu ne correspond aux filtres choisis.';
    }
}
