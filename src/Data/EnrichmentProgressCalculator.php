<?php
/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

declare(strict_types=1);

namespace EnrichmentProgressBundle\Data;

use EnrichmentProgressBundle\EnrichmentProgress\EnrichmentProgressService;
use EnrichmentProgressBundle\Service\EnrichmentService;
use Pimcore\Model\DataObject\Data\CalculatedValue;
use Pimcore\Model\DataObject\Concrete;

class EnrichmentProgressCalculator
{
    /**
     * @param Concrete $object
     * @param CalculatedValue $context
     * @return int
     */
    public static function compute(Concrete $object, CalculatedValue $context): int
    {
        /** @var EnrichmentProgressService $service */
        $service = \Pimcore::getContainer()->get(EnrichmentProgressService::class);

        return $service->getEnrichmentProgress($object)->getValueInPercent();
    }
}
