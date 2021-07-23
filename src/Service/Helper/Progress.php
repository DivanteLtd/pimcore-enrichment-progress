<?php
/**
 * @date        22/11/2017
 *
 * @author      Korneliusz Kirsz <kkirsz@divante.pl>
 * @copyright   Copyright (c) 2021 DIVANTE (http://divante.pl)
 */

declare(strict_types=1);

namespace EnrichmentProgressBundle\Service\Helper;

class Progress
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
     * Progress constructor.
     *
     * @param int $completed
     * @param int $total
     */
    public function __construct(int $completed, int $total)
    {
        $this->completed = max($completed, 0);
        $this->total = max($total, 0);
    }

    /**
     * @return int
     */
    public function completed(): int
    {
        return $this->completed;
    }

    /**
     * @return int
     */
    public function total(): int
    {
        return $this->total;
    }

    /**
     * @param Progress $progress
     */
    public function add(Progress $progress)
    {
        $this->completed += $progress->completed();
        $this->total += $progress->total();
    }
}
