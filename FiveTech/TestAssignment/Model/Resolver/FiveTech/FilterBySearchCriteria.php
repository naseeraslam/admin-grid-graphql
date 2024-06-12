<?php

namespace FiveTech\TestAssignment\Model\Resolver\FiveTech;

use Magento\Framework\Api\SearchCriteriaInterface;
use FiveTech\TestAssignment\Api\Data\FiveTechSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\InputException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\ArgumentApplier\Filter;
use Magento\Store\Api\Data\StoreInterface;
use FiveTech\TestAssignment\Model\ResourceModel\FiveTechs\CollectionFactory;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder;
use FiveTech\TestAssignment\Helper\Data;

/**
 * Class get FilterBySearchCriteria
 * @package FiveTech\TestAssignment\Model\Resolver\FiveTech
 */
class FilterBySearchCriteria implements ResolverInterface
{
    /**
     * @var Data
     */
    protected Data $data;

    /**
     * @var FiveTechSearchResultsInterfaceFactory
     */
    protected FiveTechSearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected CollectionProcessorInterface $collectionProcessor;

    protected CollectionFactory $finderCollectionFactory;

    /**
     * @var Builder
     */
    private Builder $searchCriteriaBuilder;

    /**
     * @param Builder $searchCriteriaBuilder
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $finderCollectionFactory
     * @param FiveTechSearchResultsInterfaceFactory $searchResultsFactory
     * @param Data $data
     */
    public function __construct(
        Builder $searchCriteriaBuilder,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $finderCollectionFactory,
        FiveTechSearchResultsInterfaceFactory $searchResultsFactory,
        Data $data
    ) {
        $this->data = $data;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->finderCollectionFactory = $finderCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $store = $context->getExtensionAttributes()->getStore();

        if (isset($args['currentPage']) && $args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }

        if (isset($args['pageSize']) && $args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }

        try {
            $filterResult = $this->getFiveTechResult($args, $store);
        } catch (InputException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }

        return $filterResult;
    }

    /**
     * Search for sliders
     *
     * @param array $criteria
     * @param StoreInterface $store
     * @return mixed
     * @throws InputException|GraphQlInputException
     */
    public function getFiveTechResult(array $criteria, StoreInterface $store)
    {
        $criteria[Filter::ARGUMENT_NAME] = $this->data->formatMatchFilters($criteria['filters'], $store);
        $searchCriteria = $this->searchCriteriaBuilder->build('fiveTechs', $criteria);
        $pageSize = $criteria['pageSize'] ?? 20;
        $currentPage = $criteria['currentPage'] ?? 1;
        $searchCriteria->setPageSize($pageSize)->setCurrentPage($currentPage);
        $finders = $this->getList($searchCriteria);

        $totalPages = 0;
        if ($finders->getTotalCount() > 0 && $searchCriteria->getPageSize() > 0) {
            $totalPages = ceil($finders->getTotalCount() / $searchCriteria->getPageSize());
        }
        if ($searchCriteria->getCurrentPage() > $totalPages && $finders->getTotalCount() > 0) {
            throw new GraphQlInputException(
                __(
                    'currentPage value %1 specified is greater than the %2 page(s) available.',
                    [$searchCriteria->getCurrentPage(), $totalPages]
                )
            );
        }
        $items = $finders->getItems();
        $findersData = [];
        if ($items) {
            foreach ($items as $item) {
                $findersData[] = $this->getFinderData($item);
            }
        }
        return [
            'items' => $findersData,
            'total_count' => $finders->getTotalCount(),
            'page_info' => [
                'total_pages' => $totalPages,
                'page_size' => $searchCriteria->getPageSize(),
                'current_page' => $searchCriteria->getCurrentPage(),
            ]
        ];
    }

    /**
     * @param $result
     * @return array
     */
    protected function getFinderData($result): array
    {
        return [
            'entity_id' => $result->getEntityId(),
            'name' => $result->getName(),
            'description' => $result->getDescription(),
            'country' => $result->getCountry(),
            'created_at' => $result->getCreatedAt(),
            'updated_at' => $result->getUpdatedAt()
        ];
    }

    /**
     * @inheritDoc
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->finderCollectionFactory->create();
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
}
