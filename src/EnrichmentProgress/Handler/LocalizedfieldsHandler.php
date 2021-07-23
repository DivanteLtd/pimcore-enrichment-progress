<?php
/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

declare(strict_types=1);

namespace EnrichmentProgressBundle\EnrichmentProgress\Handler;

use EnrichmentProgressBundle\EnrichmentProgress\DependencyInjection;
use EnrichmentProgressBundle\Model\EnrichmentProgress;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\Localizedfield;
use Symfony\Component\DependencyInjection\ServiceLocator;

class LocalizedfieldsHandler implements HandlerInterface, DependencyInjection\ServiceLocatorAwareInterface
{
    use DependencyInjection\ServiceLocatorAwareTrait;

    /**
     * LocalizedfieldsHandler constructor.
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
    }

    /**
     * @param Data\Localizedfields $field
     * @param Localizedfield $data
     * @return EnrichmentProgress
     */
    public function getEnrichmentProgress(Data $field, $data): EnrichmentProgress
    {
        if (!$field instanceof Data\Localizedfields) {
            throw new \InvalidArgumentException(sprintf(
                "Field must be '%s', '%s' given",
                Data\Localizedfields::class,
                get_class($field)
            ));
        }

        if (!$data instanceof Localizedfield) {
            throw new \InvalidArgumentException(sprintf(
                "Data must be '%s', '%s' given",
                Localizedfield::class,
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        return $this->checkEnrichmentProgress($field, $data);
    }

    /**
     * @param Data\Localizedfields $field
     * @param Localizedfield $data
     * @return EnrichmentProgress
     */
    protected function checkEnrichmentProgress(Data\Localizedfields $field, Localizedfield $data): EnrichmentProgress
    {
        $progress = new EnrichmentProgress();

        $languages = $this->getLanguages();
        foreach ($this->getChildren($field) as $child) {
            $handler = $this->getHandler($child);
            if ($handler) {
                foreach ($languages as $language) {
                    $progress = $progress->add($handler->getEnrichmentProgress(
                        $child,
                        $data->getLocalizedValue($child->getName(), $language, true)
                    ));
                }
            }
        }

        return $progress;
    }

    /**
     * @param Data\Localizedfields $field
     * @return Data[]
     */
    protected function getChildren(Data\Localizedfields $field): array
    {
        return $field->getFieldDefinitions(['suppressEnrichment' => true]);
    }

    /**
     * @return array
     */
    protected function getLanguages(): array
    {
        return \Pimcore\Tool::getValidLanguages();
    }
}
