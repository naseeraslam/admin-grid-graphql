<?php

namespace FiveTech\TestAssignment\Controller\Adminhtml\Record;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use FiveTech\TestAssignment\Controller\Adminhtml\Record;

/**
 * Class NewAction
 * @package FiveTech\TestAssignment\Controller\Adminhtml\Template
 */
class NewAction extends Record implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'FiveTech_TestAssignment::FiveTechs_update';

    protected $container;

    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
