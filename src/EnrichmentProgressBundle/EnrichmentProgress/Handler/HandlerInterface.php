<?php
/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

declare(strict_types=1);

namespace Divante\EnrichmentProgressBundle\EnrichmentProgress\Handler;

use Divante\EnrichmentProgressBundle\Model\EnrichmentProgress;
use Pimcore\Model\DataObject\ClassDefinition\Data;

/**
 * Interface HandlerInterface
 * @package Divante\EnrichmentProgressBundle\EnrichmentProgress\Handler
 */
interface HandlerInterface
{
    /**
     * @param Data $field
     * @param mixed $data
     * @return EnrichmentProgress
     */
    public function getEnrichmentProgress(Data $field, $data): EnrichmentProgress;
}
