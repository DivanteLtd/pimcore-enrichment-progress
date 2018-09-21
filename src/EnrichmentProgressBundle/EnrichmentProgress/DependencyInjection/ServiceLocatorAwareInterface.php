<?php
/**
 * @category    enrichment-progress
 * @date        21/09/2018 08:23
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace Divante\EnrichmentProgressBundle\EnrichmentProgress\DependencyInjection;

use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Interface ServiceLocatorAwareInterface
 * @package Divante\EnrichmentProgressBundle\EnrichmentProgress\DependencyInjection
 */
interface ServiceLocatorAwareInterface
{
    /**
     * @param ServiceLocator $serviceLocator
     */
    public function setServiceLocator(ServiceLocator $serviceLocator): void;

    /**
     * @return ServiceLocator
     */
    public function getServiceLocator(): ServiceLocator;
}
