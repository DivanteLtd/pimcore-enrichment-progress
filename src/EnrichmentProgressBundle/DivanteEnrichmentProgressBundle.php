<?php
/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\EnrichmentProgressBundle;

use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

/**
 * Class EnrichmentProgressBundle
 * @package Divante\EnrichmentProgressBundle
 */
class DivanteEnrichmentProgressBundle extends AbstractPimcoreBundle
{
    use PackageVersionTrait;

    /**
     * @return string
     */
    public function getComposerPackageName(): string
    {
        return 'divante/enrichment-progress';
    }

    /**
     * @return string
     */
    public function getNiceName()
    {
        return 'Enrichment Progress';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'This is a package that contains custom data type that allows to monitor enrichment progress of objects';
    }

    /**
     * @return array
     */
    public function getJsPaths()
    {
        return [
            '/bundles/divanteenrichmentprogress/js/pimcore/object/classes/data/enrichmentProgress.js',
            '/bundles/divanteenrichmentprogress/js/pimcore/object/tags/enrichmentProgress.js',
        ];
    }

    /**
     * @return array
     */
    public function getCssPaths()
    {
        return [
            '/bundles/divanteenrichmentprogress/css/style.css',
        ];
    }
}
