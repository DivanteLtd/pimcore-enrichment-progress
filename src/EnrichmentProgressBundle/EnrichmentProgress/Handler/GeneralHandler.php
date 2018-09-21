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
use Pimcore\Model\Element\ValidationException;

/**
 * Class GeneralHandler
 * @package Divante\EnrichmentProgressBundle\EnrichmentProgress\Handler
 */
class GeneralHandler implements HandlerInterface
{
    /**
     * @param Data $field
     * @param mixed $data
     * @return EnrichmentProgress
     */
    public function getEnrichmentProgress(Data $field, $data): EnrichmentProgress
    {
        $completed = $total = 0;

        if ($field->getMandatory()) {
            if ($this->isValid($field, $data)) {
                $completed = 1;
            }
            $total = 1;
        }

        return new EnrichmentProgress($completed, $total);
    }

    /**
     * @param Data $field
     * @param $data
     * @return bool
     */
    protected function isValid(Data $field, $data): bool
    {
        $isValid = true;

        try {
            $field->checkValidity($data);
        } catch (ValidationException $ex) {
            $isValid = false;
        }

        return $isValid;
    }
}
