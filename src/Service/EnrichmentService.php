<?php
/**
 * @date        23/10/2017
 *
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2021 DIVANTE (http://divante.pl)
 */


declare(strict_types=1);

namespace EnrichmentProgressBundle\Service;

use EnrichmentProgressBundle\Service\Helper\Progress;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\Localizedfield;
use Pimcore\Model\DataObject\Objectbrick;
use Pimcore\Model\Element\ValidationException;

class EnrichmentService
{
    /**
     * @var array
     */
    protected $languages;

    /**
     * @param int $id
     *
     * @return AbstractObject
     *
     * @throws \UnexpectedValueException
     */
    public function getObject(int $id): AbstractObject
    {
        $object = AbstractObject::getById($id);
        if (!$object instanceof AbstractObject) {
            $message = "No object found with ID '{$id}'";
            throw new \UnexpectedValueException($message);
        }

        return $object;
    }

    /**
     * @param AbstractObject $object
     *
     * @return Progress
     */
    public function getProgress(AbstractObject $object): Progress
    {
        $progress = new Progress(0, 0);

        $object = $this->getLatestVersion($object);
        $class = $object->getClass();
        $fields = $class->getFieldDefinitions();

        foreach ($fields as $field) {
            if ($field->getFieldtype() !== 'enrichmentProgress') {
                // first try
                $name = $field->getName();
                $getter = 'get' . ucfirst($name);
                $data = $object->$getter();
                $result = $this->checkProgress($field, $data);

                // second try
                if ($result->completed() < $result->total() && $class->getAllowInherit()) {
                    $getInheritedValues = AbstractObject::doGetInheritedValues();
                    AbstractObject::setGetInheritedValues(true);
                    $data = $object->$getter();
                    AbstractObject::setGetInheritedValues($getInheritedValues);
                    $result = $this->checkProgress($field, $data);
                }

                $progress->add($result);
            }
        }

        return $progress;
    }

    /**
     * @param AbstractObject $object
     *
     * @return AbstractObject
     */
    protected function getLatestVersion(AbstractObject $object): AbstractObject
    {
        $version = $object->getLatestVersion();
        if (is_object($version)) {
            $object = $version->loadData();
        }

        return $object;
    }

    /**
     * @param Data $fieldDefinition
     * @param mixed $data
     *
     * @return Progress
     */
    protected function checkProgress(Data $fieldDefinition, $data): Progress
    {
        if ($data instanceof Localizedfield) {
            return $this->checkLocalizedfieldProgress($fieldDefinition, $data);
        }

        if ($data instanceof Objectbrick) {
            return $this->checkObjectbrickProgress($fieldDefinition, $data);
        }

        if ($fieldDefinition->getMandatory()) {
            $isValid = $this->checkValidity($fieldDefinition, $data);
            $completed = $isValid ? 1 : 0;

            return new Progress($completed, 1);
        }

        return new Progress(0, 0);
    }

    /**
     * @param Data $fieldDefinition
     * @param Localizedfield $data
     *
     * @return Progress
     */
    protected function checkLocalizedfieldProgress(Data $fieldDefinition, Localizedfield $data): Progress
    {
        $progress = new Progress(0, 0);

        $fields = $fieldDefinition->getFieldDefinitions();
        $languages = $this->getLanguages();

        foreach ($fields as $field) {
            $name = $field->getName();
            foreach ($languages as $language) {
                $result = $this->checkProgress($field, $data->getLocalizedValue($name, $language));
                if ($result->completed() == $result->total()) {
                    break;
                }
            }
            $progress->add($result);
        }

        return $progress;
    }

    /**
     * @param Data $fieldDefinition
     * @param Objectbrick $data
     *
     * @return Progress
     */
    protected function checkObjectbrickProgress(Data $fieldDefinition, Objectbrick $data): Progress
    {
        $progress = new Progress(0, 0);

        $items = $data->getItems();
        foreach ($items as $item) {
            if ($item->getDoDelete()) {
                continue;
            }

            if (!$item instanceof Objectbrick\Data\AbstractData) {
                continue;
            }

            try {
                $collectionDef = Objectbrick\Definition::getByKey($item->getType());
            } catch (\Exception $exception) {
                continue;
            }

            if (!$item->getFieldname()) {
                $item->setFieldname($data->getFieldname());
            }

            foreach ($collectionDef->getFieldDefinitions() as $fd) {
                $key = $fd->getName();
                $getter = 'get' . ucfirst($key);
                $result = $this->checkProgress($fd, $item->$getter());
                $progress->add($result);
            }
        }

        return $progress;
    }

    /**
     * @return array
     */
    protected function getLanguages(): array
    {
        if (null === $this->languages) {
            $config = \Pimcore\Config::getSystemConfig();
            $this->languages = explode(',', $config->general->validLanguages);
        }

        return $this->languages;
    }

    /**
     * @param Data $fieldDefinition
     * @param mixed $data
     *
     * @return bool
     */
    protected function checkValidity(Data $fieldDefinition, $data): bool
    {
        if ($fieldDefinition instanceof Data\Checkbox && $data === false) {
            $data = null;
        }

        try {
            $fieldDefinition->checkValidity($data);
            $isValid = true;
        } catch (ValidationException $ex) {
            $isValid = false;
        }

        return $isValid;
    }
}
