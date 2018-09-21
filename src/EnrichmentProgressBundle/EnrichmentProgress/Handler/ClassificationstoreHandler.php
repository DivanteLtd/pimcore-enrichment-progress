<?php
/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

declare(strict_types=1);

namespace Divante\EnrichmentProgressBundle\EnrichmentProgress\Handler;

use Divante\EnrichmentProgressBundle\EnrichmentProgress\DependencyInjection;
use Divante\EnrichmentProgressBundle\Model\EnrichmentProgress;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\Classificationstore;
use Symfony\Component\DependencyInjection\ServiceLocator;

class ClassificationstoreHandler implements HandlerInterface, DependencyInjection\ServiceLocatorAwareInterface
{
    use DependencyInjection\ServiceLocatorAwareTrait;

    /**
     * ClassificationstoreHandler constructor.
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
    }

    /**
     * @param Data\Classificationstore $field
     * @param Classificationstore $data
     * @return EnrichmentProgress
     */
    public function getEnrichmentProgress(Data $field, $data): EnrichmentProgress
    {
        if (!$field instanceof Data\Classificationstore) {
            throw new \InvalidArgumentException(sprintf(
                "Field must be '%s', '%s' given",
                Data\Classificationstore::class,
                get_class($field)
            ));
        }

        if (!$data instanceof Classificationstore) {
            throw new \InvalidArgumentException(sprintf(
                "Data must be '%s', '%s' given",
                Classificationstore::class,
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        return $this->checkEnrichmentProgress($field, $data);
    }

    /**
     * @param Data\Classificationstore $field
     * @param Classificationstore $data
     * @return EnrichmentProgress
     */
    protected function checkEnrichmentProgress(
        Data\Classificationstore $field,
        Classificationstore $data
    ): EnrichmentProgress {
        $progress = new EnrichmentProgress();

        $languages = $field->getValidLanguages();
        foreach ($this->getGroups($data) as $group) {
            /** @var Classificationstore\KeyGroupRelation[] $relations */
            $relations = $group->getRelations();
            foreach ($relations as $relation) {
                $child = $this->getChild($relation);
                $handler = $this->getHandler($child);
                if ($handler) {
                    foreach ($languages as $language) {
                        $progress = $progress->add($handler->getEnrichmentProgress(
                            $child,
                            $this->getLocalizedKeyValue($data, $relation, $language)
                        ));
                    }
                }
            }
        }

        return $progress;
    }

    /**
     * @param Classificationstore $data
     * @return Classificationstore\GroupConfig[]
     */
    protected function getGroups(Classificationstore $data): array
    {
        $groups = [];

        foreach ($data->getActiveGroups() as $id => $enabled) {
            if ((bool) $enabled) {
                $groups[] = $this->getGroupById((int) $id);
            }
        }

        return $groups;
    }

    /**
     * @param int $id
     * @return Classificationstore\GroupConfig
     */
    protected function getGroupById(int $id): Classificationstore\GroupConfig
    {
        $group = Classificationstore\GroupConfig::getById($id);

        if (!$group instanceof Classificationstore\GroupConfig) {
            throw new \UnexpectedValueException(sprintf("No group config was found with ID %d", $id));
        }

        return $group;
    }

    /**
     * @param Classificationstore\KeyGroupRelation $relation
     * @return Data
     */
    protected function getChild(Classificationstore\KeyGroupRelation $relation): Data
    {
        $field = Classificationstore\Service::getFieldDefinitionFromJson(
            json_decode($relation->getDefinition()),
            $relation->getType()
        );

        if ($relation->isMandatory()) {
            $field->setMandatory(true);
        }

        return $field;
    }

    /**
     * @param Classificationstore $data
     * @param Classificationstore\KeyGroupRelation $relation
     * @param string $language
     * @return mixed
     */
    protected function getLocalizedKeyValue(
        Classificationstore $data,
        Classificationstore\KeyGroupRelation $relation,
        string $language
    ) {
        return $data->getLocalizedKeyValue(
            $relation->getGroupId(),
            $relation->getKeyId(),
            $language,
            true,
            true
        );
    }
}
