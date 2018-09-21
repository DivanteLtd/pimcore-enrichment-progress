<?php
/**
 * @category    enrichment-progress
 * @date        21/09/2018 08:27
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace Divante\EnrichmentProgressBundle\EnrichmentProgress\DependencyInjection;

use Divante\EnrichmentProgressBundle\Data\EnrichmentProgress;
use Divante\EnrichmentProgressBundle\EnrichmentProgress\Handler\HandlerInterface;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Trait ServiceLocatorAwareTrait
 * @package Divante\EnrichmentProgressBundle\EnrichmentProgress\DependencyInjection
 */
trait ServiceLocatorAwareTrait
{
    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * @param ServiceLocator $serviceLocator
     */
    public function setServiceLocator(ServiceLocator $serviceLocator): void
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return ServiceLocator
     */
    public function getServiceLocator(): ServiceLocator
    {
        return $this->serviceLocator;
    }

    /**
     * @param Data $field
     * @param string $defaultId
     * @return HandlerInterface|null
     */
    protected function getHandler(Data $field, string $defaultId = 'general'): ?HandlerInterface
    {
        if (!$field instanceof EnrichmentProgress) {
            $id = $this->getServiceLocator()->has($field->getFieldtype()) ? $field->getFieldtype() : $defaultId;
            return $this->getServiceLocator()->get($id);
        }
    }
}
