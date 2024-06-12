<?php

declare(strict_types=1);

namespace FiveTech\TestAssignment\Controller\Adminhtml\Record;

use FiveTech\TestAssignment\Controller\Adminhtml\Record;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use FiveTech\TestAssignment\Api\Data\FiveTechInterfaceFactory;

class InlineEdit extends Record
{
    const ADMIN_RESOURCE = 'FiveTech_TestAssignment::FiveTechs_update';

    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonFactory;

    /**
     * @var FiveTechInterfaceFactory
     */
    protected FiveTechInterfaceFactory $record;

    /**
     * @param Context $context
     * @param FiveTechInterfaceFactory $record
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        FiveTechInterfaceFactory $record,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->record = $record;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Inline edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $modelid) {
                    $model = $this->record->create()->load($modelid);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$modelid]));
                        $model->save();
                    } catch (\Exception $e) {
                        $messages[] = "[FiveTech ID: {$modelid}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}

