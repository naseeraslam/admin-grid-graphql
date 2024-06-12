<?php

declare(strict_types=1);

namespace FiveTech\TestAssignment\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use FiveTech\TestAssignment\Api\Data\FiveTechInterface;
use FiveTech\TestAssignment\Api\Data\FiveTechInterfaceFactory;
use FiveTech\TestAssignment\Api\Data\FiveTechSearchResultsInterfaceFactory;
use FiveTech\TestAssignment\Api\FiveTechsRepositoryInterface;
use FiveTech\TestAssignment\Model\ResourceModel\FiveTechs as ResourceFiveTechs;
use FiveTech\TestAssignment\Model\ResourceModel\FiveTechs\CollectionFactory as FiveTechsCollectionFactory;

class FiveTechsRepository implements FiveTechsRepositoryInterface
{

    /**
     * Column name  to filter
     */
    const ENTITY_ID = 'entity_id';

    /**
     * @var CollectionProcessorInterface
     */
    protected CollectionProcessorInterface $collectionProcessor;

    /**
     * @var FiveTechInterfaceFactory
     */
    protected FiveTechInterfaceFactory $fiveTechsFactory;

    /**
     * @var FiveTechsCollectionFactory
     */
    protected FiveTechsCollectionFactory $fiveTechsCollectionFactory;

    /**
     * @var FiveTechs
     */
    protected $searchResultsFactory;

    /**
     * @var ResourceFiveTechs
     */
    protected ResourceFiveTechs $resource;
    
    /**
     * @param ResourceFiveTechs $resource
     * @param StorevariablesInterfaceFactory $fiveTechsFactory
     * @param FiveTechsCollectionFactory $FiveTechsCollectionFactory
     * @param StorevariablesSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceFiveTechs  $resource,
        FiveTechInterfaceFactory $fiveTechsFactory,
        FiveTechsCollectionFactory $fiveTechsCollectionFactory,
        FiveTechSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->fiveTechsFactory = $fiveTechsFactory;
        $this->fiveTechsCollectionFactory = $fiveTechsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(FiveTechInterface $fiveTech): FiveTechInterface
    {
        try {
            $this->resource->save($fiveTech);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the fiveTechs: %1',
                $exception->getMessage()
            ));
        }
        
        return $fiveTech;
    }

    /**
     * @param $fiveTechId
     * @return FiveTechInterface
     * @throws NoSuchEntityException
     */
    public function get($fiveTechId)
    {
        $fiveTechs = $this->fiveTechsFactory->create();
        $this->resource->load($fiveTechs, $fiveTechId);
        if (!$fiveTechs->getId()) {
            throw new NoSuchEntityException(__('FiveTech with id "%1" does not exist.', $fiveTechId));
        }
        return $fiveTechs;
    }
    
    /**
     * @inheritDoc
     */
    public function getList(
        SearchCriteriaInterface $criteria
    )
    {
        $collection = $this->fiveTechsCollectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $items = [];
        
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(FiveTechInterface $fiveTechs): bool
    {
        try {
            $fiveTechsModel = $this->fiveTechsFactory->create();
            $this->resource->load($fiveTechsModel, $fiveTechs->getEntityId());
            $this->resource->delete($fiveTechsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the FiveTechs: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($fiveTechId): bool
    {
        return $this->delete($this->get($fiveTechId));
    }
}

