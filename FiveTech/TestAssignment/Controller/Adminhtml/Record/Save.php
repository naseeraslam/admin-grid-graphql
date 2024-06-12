<?php

namespace FiveTech\TestAssignment\Controller\Adminhtml\Record;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use FiveTech\TestAssignment\Api\Data\FiveTechInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime as Date;
use FiveTech\TestAssignment\Controller\Adminhtml\Record;

/**
 * Class Save
 * @package FiveTech\TestAssignment\Controller\Adminhtml\Record
 */
class Save extends Record implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'FiveTech_TestAssignment::FiveTechs_save';

    /**
     * @var Date
     */
    private Date $dateFilter;

    /**
     * @var FiveTechInterfaceFactory
     */
    protected FiveTechInterfaceFactory $record;

    /**
     * @param Context $context
     * @param Date $dateFilter
     * @param FiveTechInterfaceFactory $record
     */
    public function __construct(
        Context $context,
        Date $dateFilter,
        FiveTechInterfaceFactory $record
    ) {
        parent::__construct($context);
        $this->record = $record;
        $this->dateFilter = $dateFilter;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');

            $model = $this->record->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This FiveTechs record no longer exists.'));

                return $resultRedirect->setPath('*/*/');
            }
            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the FiveTechs record.'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the FiveTechs.'));
            }

            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
