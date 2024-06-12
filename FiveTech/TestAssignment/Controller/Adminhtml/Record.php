<?php

namespace FiveTech\TestAssignment\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

/**
 * Abstract Class Record
 * @package FiveTech\TestAssignment\Controller\Adminhtml
 */
abstract class Record extends Action
{
    const ADMIN_RESOURCE = 'FiveTech_TestAssignment::FiveTechs';

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('FiveTech_TestAssignment::FiveTechs')
            ->_addBreadcrumb(__('FiveTech Records'), __('FiveTech Records'));
        return $this;
    }
}
