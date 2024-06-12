<?php

declare(strict_types=1);

namespace FiveTech\TestAssignment\Model\ResourceModel\FiveTechs;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \FiveTech\TestAssignment\Model\FiveTechs::class,
            \FiveTech\TestAssignment\Model\ResourceModel\FiveTechs::class
        );
    }
}

