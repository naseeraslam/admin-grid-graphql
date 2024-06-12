<?php

namespace FiveTech\TestAssignment\Controller\Adminhtml\Record;

use FiveTech\TestAssignment\Controller\Adminhtml\Record;
use FiveTech\TestAssignment\Api\Data\FiveTechInterfaceFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Delete for deleting FiveTech Record
 * @package FiveTech\TestAssignment\Controller\Adminhtml\Record
 */
class Delete extends Record
{
    const ADMIN_RESOURCE = 'FiveTech_TestAssignment::FiveTechs_delete';

    /**
     * @var FiveTechInterfaceFactory
     */
    protected FiveTechInterfaceFactory $record;

    /**
     * @param Context $context
     * @param FiveTechInterfaceFactory $record
     */
    public function __construct(
        Context $context,
        FiveTechInterfaceFactory $record
    ) {
        parent::__construct($context);
        $this->record = $record;
    }

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('entity_id');

        if ($id) {
            try {
                $model = $this->record->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the fiveTech record.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a fiveTech record to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
