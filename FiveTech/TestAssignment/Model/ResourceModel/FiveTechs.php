<?php

declare(strict_types=1);

namespace FiveTech\TestAssignment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class FiveTechs extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('five_tech', 'entity_id');
    }
}

