<?php
/**
 * @date       23.07.2021
 * @author      Jakub Płaskonka <jplaskonka@divante.pl>
 * @copyright   Copyright (c) 2021 DIVANTE (http://divante.com/)
 */

declare(strict_types=1);

namespace EnrichmentProgressBundle\Controller;

use EnrichmentProgressBundle\EnrichmentProgress\EnrichmentProgressService;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EnrichmentController
 *
 * @package Divante\EnrichmentBundle\Controller
 */
#[Route('/enrichment')]
class EnrichmentController extends FrontendController
{
    private EnrichmentProgressService $service;

    public function __construct(EnrichmentProgressService $service)
    {
        $this->service = $service;
    }


    #[Route('/progress/{id}', requirements: ['id' => '[1-9][0-9]*'])]
    public function progressAction(string $id): JsonResponse
    {
        $object = DataObject::getById($id);
        if (!$object) {
            return $this->json([
                'completed' => 0,
                'total' => 0,
            ]);
        }
        $progress = $this->service->getEnrichmentProgress($object);

        return $this->json([
            'completed' => $progress->getCompleted(),
            'total' => $progress->getTotal(),
        ]);
    }
}
