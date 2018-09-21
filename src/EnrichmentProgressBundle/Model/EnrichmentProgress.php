<?php
/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

declare(strict_types=1);

namespace Divante\EnrichmentProgressBundle\Model;

/**
 * Class EnrichmentProgress
 * @package Divante\EnrichmentProgressBundle\Model
 */
class EnrichmentProgress
{
    /**
     * @var int
     */
    protected $completed;

    /**
     * @var int
     */
    protected $total;

    /**
     * EnrichmentProgress constructor.
     * @param int $completed
     * @param int $total
     */
    public function __construct(int $completed = 0, int $total = 0)
    {
        $this->completed = max(0, $completed);
        $this->total = max(0, $total);
    }

    /**
     * @return int
     */
    public function getCompleted(): int
    {
        return $this->completed;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getValueInPercent(): int
    {
        if ($this->total > 0) {
            return (int) round(100 * $this->completed / $this->total);
        }

        return 100;
    }

    /**
     * @param EnrichmentProgress $enrichmentProgress
     * @return EnrichmentProgress
     */
    public function add(EnrichmentProgress $enrichmentProgress): EnrichmentProgress
    {
        $completed = $this->getCompleted() + $enrichmentProgress->getCompleted();
        $total = $this->getTotal() + $enrichmentProgress->getTotal();

        return new EnrichmentProgress($completed, $total);
    }
}
