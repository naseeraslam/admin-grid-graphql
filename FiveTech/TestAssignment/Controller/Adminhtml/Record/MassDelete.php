<?php

namespace FiveTech\TestAssignment\Controller\Adminhtml\Record;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use FiveTech\TestAssignment\Api\FiveTechsRepositoryInterface;
use FiveTech\TestAssignment\Controller\Adminhtml\Record;
use FiveTech\TestAssignment\Api\Data\FiveTechInterfaceFactory;

/**
 * Class MassDelete
 * @package FiveTech\TestAssignment\Controller\Adminhtml\Record
 */
class MassDelete extends Record implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'FiveTech_TestAssignment::FiveTechs_delete';

    /**
     * @var FiveTechsRepositoryInterface
     */
    protected FiveTechsRepositoryInterface $recordRepository;

    /**
     * @var FiveTechInterfaceFactory
     */
    protected FiveTechInterfaceFactory $record;

    /**
     * @var Filter
     */
    protected Filter $filter;

    /**
     * @param Context $context
     * @param FiveTechInterfaceFactory $record
     * @param FiveTechsRepositoryInterface $recordRepository
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        FiveTechInterfaceFactory $record,
        FiveTechsRepositoryInterface $recordRepository,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->recordRepository = $recordRepository;
        $this->filter = $filter;
        $this->record = $record;
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->record->create()->getCollection());
        $templateCollection = $collection->getData();
        $collectionSize = $collection->getSize();
        foreach ($templateCollection as $template) {
            $this->recordRepository->deleteById($template['entity_id']);
        }
        try {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong. Please try again.'));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
