<?php

namespace FiveTech\TestAssignment\Controller\Adminhtml\Record;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use FiveTech\TestAssignment\Controller\Adminhtml\Record;
use FiveTech\TestAssignment\Api\Data\FiveTechInterfaceFactory;

/**
 * Class Edit for editing FiveTech Record
 * @package FiveTech\TestAssignment\Controller\Adminhtml\Record
 */
class Edit extends Record
{
    const ADMIN_RESOURCE = 'FiveTech_TestAssignment::FiveTechs_update';

    /**
     * @var FiveTechInterfaceFactory
     */
    protected FiveTechInterfaceFactory $record;

    /**
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FiveTechInterfaceFactory $record
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FiveTechInterfaceFactory $record
    ) {
        parent::__construct($context);
        $this->coreRegistry = $registry;
        $this->record = $record;
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');

        $model = $this->record->create();
        if ($entityId) {
            $model->load($entityId);
            if (!$model->getEntityId()) {
                $this->messageManager->addErrorMessage(__('This fiveTech record no longer exist.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->coreRegistry->register('row_data', $model);
        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('FiveTech Record'));
        $this->_view->renderLayout();
    }
}
