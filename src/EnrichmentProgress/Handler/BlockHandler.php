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
use Pimcore\Model\DataObject\Data\BlockElement;
use Symfony\Component\DependencyInjection\ServiceLocator;

class BlockHandler implements HandlerInterface, DependencyInjection\ServiceLocatorAwareInterface
{
    use DependencyInjection\ServiceLocatorAwareTrait;

    /**
     * BlockHandler constructor.
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
    }

    /**
     * @param Data\Block $field
     * @param array|null $data
     * @return EnrichmentProgress
     */
    public function getEnrichmentProgress(Data $field, $data): EnrichmentProgress
    {
        if (!$field instanceof Data\Block) {
            throw new \InvalidArgumentException(sprintf(
                "Field must be '%s', '%s' given",
                Data\Block::class,
                get_class($field)
            ));
        }

        if (is_null($data)) {
            $data = [];
        }

        if (!is_array($data)) {
            throw new \InvalidArgumentException(sprintf(
                "Data must be '%s', '%s' given",
                'array',
                gettype($data)
            ));
        }

        return $this->checkEnrichmentProgress($field, $data);
    }

    /**
     * @param Data\Block $field
     * @param array $data
     * @return EnrichmentProgress
     */
    protected function checkEnrichmentProgress(Data\Block $field, array $data): EnrichmentProgress
    {
        $progress = new EnrichmentProgress();

        foreach ($this->getChildren($field) as $child) {
            $handler = $this->getHandler($child);
            if ($handler) {
                $name = $child->getName();
                foreach ($data as $block) {
                    /** @var BlockElement $element */
                    $element = $block[$name];
                    $progress = $progress->add($handler->getEnrichmentProgress($child, $element->getData()));
                }
            }
        }

        return $progress;
    }

    /**
     * @param Data\Block $field
     * @return Data[]
     */
    protected function getChildren(Data\Block $field): array
    {
        return $field->getFieldDefinitions(['suppressEnrichment' => true]);
    }
}
