<?php

declare(strict_types=1);

namespace FiveTech\TestAssignment\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use FiveTech\TestAssignment\Api\Data\FiveTechInterface;

interface FiveTechsRepositoryInterface
{

    /**
     * Save FiveTech Record
     * @param FiveTechInterface $fiveTech
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechInterface
     * @throws LocalizedException
     */
    public function save(
        FiveTechInterface $fiveTech
    );

    /**
     * Retrieve FiveTechs
     * @param string $fiveTechId
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechInterface
     * @throws LocalizedException
     */
    public function get($fiveTechId);

    /**
     * Retrieve FiveTechs matching the specified criteria.
     * @param SearchCriteriaInterface $searchCriteria
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete FiveTechs
     * @param FiveTechInterface $fiveTechs
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        FiveTechInterface $fiveTechs
    );

    /**
     * Delete FiveTech by ID
     * @param string $fiveTechId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($fiveTechId);
}

