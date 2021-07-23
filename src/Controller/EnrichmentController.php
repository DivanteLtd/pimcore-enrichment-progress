<?php
/**
 * @date       23.07.2021
 * @author      Jakub Płaskonka <jplaskonka@divante.pl>
 * @copyright   Copyright (c) 2021 DIVANTE (http://divante.com/)
 */

declare(strict_types=1);

namespace EnrichmentProgressBundle\Controller;

use EnrichmentProgressBundle\Service\EnrichmentService;
use Pimcore\Bundle\AdminBundle\Controller\AdminController;
use Pimcore\Bundle\AdminBundle\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class EnrichmentController
 *
 * @package Divante\EnrichmentBundle\Controller
 * @Route("/enrichment")
 */
class EnrichmentController extends AdminController
{
    /**
     * @var EnrichmentService
     */
    private $service;

    /**
     * @param EnrichmentService $service
     */
    public function __construct(EnrichmentService $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     * @Route("/progress/{id}", requirements={"id": "[1-9][0-9]*"})
     * @Method({"GET"})
     */
    public function progressAction(string $id): JsonResponse
    {
        $object = $this->service->getObject((int) $id);
        $progress = $this->service->getProgress($object);

        return $this->adminJson([
            'completed' => $progress->completed(),
            'total' => $progress->total(),
        ]);
    }
}
