<?php

namespace FiveTech\TestAssignment\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\InputException;
use Magento\Search\Model\Query;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;

class Data
{
    /**
     * @var string
     */
    private const SPECIAL_CHARACTERS = '-+~/\\<>\'":*$#@()!,.?`=%&^';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface      $scopeConfig,

    ) {
        $this->scopeConfig = $scopeConfig;
    }


    /**
     * @param $result
     * @return array
     */
    public function fiveTechResult($result): array
    {
        $output = [];
        if ($result->getEntityId()) {
            $output[] = [
                'entity_id' => $result->getEntityId(),
                'name' => $result->getName(),
               'description' => $result->getDescription(),
                'country' => $result->getCountry(),
                'created_at' => $result->getCreatedAt(),
                'updated_at' => $result->getUpdatedAt()
            ];
        }

        return $output;
    }

    /**
     * Format match filters to behave like fuzzy match
     *
     * @param array $filters
     * @param StoreInterface $store
     * @return array
     * @throws InputException
     */
    public function formatMatchFilters(array $filters, StoreInterface $store): array
    {
        $minQueryLength = $this->scopeConfig->getValue(
            Query::XML_PATH_MIN_QUERY_LENGTH,
            ScopeInterface::SCOPE_STORE,
            $store
        );

        foreach ($filters as $filter => $condition) {
            $conditionType = current(array_keys($condition));
            if ($conditionType === 'match') {
                $searchValue = trim(str_replace(self::SPECIAL_CHARACTERS, '', $condition[$conditionType]));
                $matchLength = strlen($searchValue);
                if ($matchLength < $minQueryLength) {
                    throw new InputException(__('Invalid match filter. Minimum length is %1.', $minQueryLength));
                }
                unset($filters[$filter]['match']);
                $filters[$filter]['like'] = '%' . $searchValue . '%';
            }
        }

        return $filters;
    }
}
