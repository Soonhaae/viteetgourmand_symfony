<?php

namespace App\Controller\Gestion;

use App\Document\StatistiquesMenu;
use App\Repository\MenuRepository;
use App\Repository\StatistiquesMenuRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/gestion/statistiques')]
final class StatistiquesController extends AbstractController
{
    #[Route('', name: 'app_gestion_statistiques', methods: ['GET'])]
    public function index(Request $request, DocumentManager $dm, MenuRepository $menuRepository): Response
    {
        $today    = new \DateTimeImmutable('today');
        $moisRef  = (int) $today->format('n');
        $anneeRef = (int) $today->format('Y');

        /** @var StatistiquesMenuRepository $repo */
        $repo = $dm->getRepository(StatistiquesMenu::class);
        $filtres = $this->getFilters($request, $anneeRef);
        $resultat = $this->calculateResultat($repo, $filtres);

        return $this->render('gestion/statistiques/index.html.twig', [
            'menus'       => $menuRepository->findBy([], ['title' => 'ASC']),
            'filtre'      => $filtres,
            'resultat'    => $resultat,
            'chartMois'   => $this->buildMoisHistory($repo, $moisRef, $anneeRef, 12),
        ]);
    }

    /**
     * Endpoint JSON pour le calcul AJAX des résultats selon les filtres.
     */
    #[Route('/resultat', name: 'app_gestion_statistiques_resultat', methods: ['GET'])]
    public function resultat(Request $request, DocumentManager $dm): JsonResponse
    {
        $today    = new \DateTimeImmutable('today');
        $anneeRef = (int) $today->format('Y');

        /** @var StatistiquesMenuRepository $repo */
        $repo = $dm->getRepository(StatistiquesMenu::class);

        $filtres = $this->getFilters($request, $anneeRef);
        $resultat = $this->calculateResultat($repo, $filtres);

        return $this->json($resultat);
    }

    /**
     * @return array{menuId: int|null, mois: int|null, annee: int}
     */
    private function getFilters(Request $request, int $anneeRef): array
    {
        $menuIdRaw = $request->query->getString('menuId');
        $moisRaw   = $request->query->getString('mois');

        $menuIdFilter = $menuIdRaw !== '' ? (int) $menuIdRaw : null;
        $moisFilter   = $moisRaw !== '' ? (int) $moisRaw : null;
        $anneeFilter  = $request->query->getInt('annee') ?: $anneeRef;

        if ($moisFilter !== null && ($moisFilter < 1 || $moisFilter > 12)) {
            $moisFilter = null;
        }

        return [
            'menuId' => $menuIdFilter,
            'mois'   => $moisFilter,
            'annee'  => $anneeFilter,
        ];
    }

    /**
     * @param array{menuId: int|null, mois: int|null, annee: int} $filtres
     * @return array{nbCommandes: int, chiffreAffaires: float}
     */
    private function calculateResultat(StatistiquesMenuRepository $repo, array $filtres): array
    {
        $docs = $repo->findForRange($filtres['annee'], $filtres['mois'] ?? 1, $filtres['annee'], $filtres['mois'] ?? 12);

        if ($filtres['menuId'] !== null) {
            $docs = array_values(array_filter($docs, fn ($d) => $d->getMenuId() === $filtres['menuId']));
        }

        return [
            'nbCommandes'     => array_sum(array_map(fn ($d) => $d->getNbCommandes(), $docs)),
            'chiffreAffaires' => round(array_sum(array_map(fn ($d) => $d->getChiffreAffaires(), $docs)), 2),
        ];
    }

    /**
     * Historique sur les N derniers mois (du plus ancien au plus récent).
     *
     * @return array{hasData: bool, chartData: array{labels: string[], datasets: array[]}}
     */
    private function buildMoisHistory(
        StatistiquesMenuRepository $repo,
        int $moisRef,
        int $anneeRef,
        int $count,
    ): array {
        // Calcul des N slots mensuels
        $slots = [];
        for ($i = $count - 1; $i >= 0; --$i) {
            $mois  = $moisRef - $i;
            $annee = $anneeRef;
            while ($mois <= 0) {
                $mois += 12;
                --$annee;
            }
            $slots[] = ['annee' => $annee, 'mois' => $mois, 'label' => sprintf('%02d/%d', $mois, $annee)];
        }

        $first = $slots[0];
        $last  = $slots[$count - 1];
        $docs  = $repo->findForRange($first['annee'], $first['mois'], $last['annee'], $last['mois']);

        return $this->buildChartData($slots, $docs, fn (StatistiquesMenu $doc) => [
            'annee' => $doc->getAnnee(),
            'mois'  => $doc->getMois(),
        ]);
    }

    /**
     * @param array<array{annee: int, mois: int|null, label: string}> $slots
     * @param StatistiquesMenu[]                                       $docs
     * @param callable(StatistiquesMenu): array{annee: int, mois: int|null} $keyFn
     * @return array{hasData: bool, chartData: array{labels: string[], datasets: array[]}}
     */
    private function buildChartData(array $slots, array $docs, callable $keyFn): array
    {
        $allMenus = []; // menuId => menuTitle
        $slotData = []; // slotIdx => menuId => nbCommandes

        foreach ($docs as $doc) {
            $allMenus[$doc->getMenuId()] = $doc->getMenuTitle();
            $docKey = $keyFn($doc);

            foreach ($slots as $idx => $slot) {
                if ($docKey['annee'] === $slot['annee'] && $docKey['mois'] === $slot['mois']) {
                    $slotData[$idx][$doc->getMenuId()] = ($slotData[$idx][$doc->getMenuId()] ?? 0) + $doc->getNbCommandes();
                    break;
                }
            }
        }

        if (empty($allMenus)) {
            return ['hasData' => false, 'chartData' => ['labels' => [], 'datasets' => []]];
        }

        asort($allMenus);
        $colors   = $this->chartColors();
        $slotKeys = array_keys($slots);
        $datasets = [];
        $colorIdx = 0;

        foreach ($allMenus as $menuId => $menuTitle) {
            $color = $colors[$colorIdx % \count($colors)];
            $datasets[] = [
                'label'            => $menuTitle,
                'data'             => array_map(fn ($k) => $slotData[$k][$menuId] ?? 0, $slotKeys),
                'borderColor'      => $color,
                'backgroundColor'  => $color,
                'tension'          => 0.3,
                'fill'             => false,
                'pointRadius'      => 4,
                'pointHoverRadius' => 6,
            ];
            ++$colorIdx;
        }

        return [
            'hasData'   => true,
            'chartData' => [
                'labels'   => array_column($slots, 'label'),
                'datasets' => $datasets,
            ],
        ];
    }

    /** @return string[] */
    private function chartColors(): array
    {
        return [
            'rgb(13,110,253)',
            'rgb(220,53,69)',
            'rgb(25,135,84)',
            'rgb(255,193,7)',
            'rgb(111,66,193)',
            'rgb(253,126,20)',
            'rgb(13,202,240)',
            'rgb(102,16,242)',
            'rgb(214,51,132)',
            'rgb(32,201,151)',
        ];
    }
}
