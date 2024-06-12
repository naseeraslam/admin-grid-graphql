<?php

namespace FiveTech\TestAssignment\Controller\Adminhtml\Record;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use FiveTech\TestAssignment\Controller\Adminhtml\Record;

/**
 * Class Index for grid display
 * @package FiveTech\TestAssignment\Controller\Adminhtml\Record
 */
class Index extends Record
{
    const ADMIN_RESOURCE = 'FiveTech_TestAssignment::FiveTechs_view';

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return Redirect|ResultInterface|void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Manage FiveTech Records'), __('Manage FiveTech Records'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Manage FiveTech Records'));
        $this->_view->renderLayout();
    }
}
