<?php
/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

declare(strict_types=1);

namespace EnrichmentProgressBundle\EnrichmentProgress;

use EnrichmentProgressBundle\EnrichmentProgress\DependencyInjection;
use EnrichmentProgressBundle\Model\EnrichmentProgress;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\DependencyInjection\ServiceLocator;

class EnrichmentProgressService implements DependencyInjection\ServiceLocatorAwareInterface
{
    use DependencyInjection\ServiceLocatorAwareTrait;

    /**
     * EnrichmentProgressService constructor.
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
    }

    /**
     * @param Concrete $object
     * @return EnrichmentProgress
     */
    public function getEnrichmentProgress(Concrete $object): EnrichmentProgress
    {
        $progress = new EnrichmentProgress();

        $class = $object->getClass();
        $fields = $class->getFieldDefinitions(['suppressEnrichment' => true]);

        /** @var Data $field */
        foreach ($fields as $field) {
            $handler = $this->getHandler($field);
            if ($handler) {
                $value = $this->getValue($object, $field);
                $childProgress = $handler->getEnrichmentProgress($field, $value);

                if ($childProgress->getCompleted() < $childProgress->getTotal() && $class->getAllowInherit()) {
                    $getInheritedValues = AbstractObject::doGetInheritedValues();
                    AbstractObject::setGetInheritedValues(true);

                    $value = $this->getValue($object, $field);
                    $childProgress = $handler->getEnrichmentProgress($field, $value);

                    AbstractObject::setGetInheritedValues($getInheritedValues);
                }

                $progress = $progress->add($childProgress);
            }
        }

        return $progress;
    }

    /**
     * @param Concrete $object
     * @param Data $field
     * @return mixed
     */
    protected function getValue(Concrete $object, Data $field)
    {
        $name = $field->getName();
        $getter = 'get' . ucfirst($name);

        return $object->$getter();
    }
}
