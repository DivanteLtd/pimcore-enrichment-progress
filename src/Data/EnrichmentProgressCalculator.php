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
use Pimcore\Model\DataObject\ClassDefinition\CalculatorClassInterface;
use Pimcore\Model\DataObject\Data\CalculatedValue;
use Pimcore\Model\DataObject\Concrete;

class EnrichmentProgressCalculator implements CalculatorClassInterface
{
    /**
     * @param Concrete $object
     * @param CalculatedValue $context
     * @return string
     */
    public function compute(Concrete $object, CalculatedValue $context): string
    {
        /** @var EnrichmentProgressService $service */
        $service = \Pimcore::getContainer()->get(EnrichmentProgressService::class);

        return (string) $service->getEnrichmentProgress($object)->getValueInPercent();
    }

    public function getCalculatedValueForEditMode(Concrete $object, CalculatedValue $context): string
    {
        return $this->compute($object, $context);
    }
}
