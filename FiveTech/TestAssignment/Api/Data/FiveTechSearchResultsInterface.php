<?php

declare(strict_types=1);

namespace FiveTech\TestAssignment\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface FiveTechSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get five_tech list.
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechInterface[]
     */
    public function getItems();

    /**
     * Set five_tech list.
     * @param \FiveTech\TestAssignment\Api\Data\FiveTechInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
