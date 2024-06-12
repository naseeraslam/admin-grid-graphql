<?php

namespace FiveTech\TestAssignment\Model\Resolver\FiveTech;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use FiveTech\TestAssignment\Api\FiveTechsRepositoryInterface;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\NoSuchEntityException;
use FiveTech\TestAssignment\Helper\Data;

/**
 * Class FindById
 */
class FindById implements ResolverInterface
{
    /**
     * @var Data
     */
    protected Data $data;

    /**
     * @var FiveTechsRepositoryInterface
     */
    protected FiveTechsRepositoryInterface $fiveTechRecord;

    /**
     * @param FiveTechsRepositoryInterface $fiveTechRecord
     * @param Data $data
     */
    public function __construct(
        FiveTechsRepositoryInterface $fiveTechRecord,
        Data $data
    ) {
        $this->data = $data;
        $this->fiveTechRecord = $fiveTechRecord;
    }

    /**
     * @param $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws GraphQlInputException
     * @throws LocalizedException
     */
    public function resolve(
        $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        $fiveTechId = $args['fiveTechId'] ?? null;

        if (!$fiveTechId) {
            throw new GraphQlInputException(__('Missing required argument "fiveTechId"'));
        }

        try {
            $fiveTechRecord = $this->fiveTechRecord->get($fiveTechId);

            return $this->data->fiveTechResult($fiveTechRecord);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new GraphQlInputException(__('An error occurred while fetching the record.'));
        }
    }
}
