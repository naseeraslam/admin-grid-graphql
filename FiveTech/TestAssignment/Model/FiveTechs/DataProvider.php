<?php

declare(strict_types=1);

namespace FiveTech\TestAssignment\Model\FiveTechs;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Customer\Model\SessionFactory;
use FiveTech\TestAssignment\Model\ResourceModel\FiveTechs\CollectionFactory;

/**
 * Class DataProvider
 * @package FiveTech\TestAssignment\Model\FiveTechs
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var SessionFactory
     */
    protected SessionFactory $sessionFactory;

    /**
     * @var array|null
     */
    private ?array $loadedData = null;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $CollectionFactory
     * @param SessionFactory $sessionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $CollectionFactory,
        SessionFactory $sessionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $CollectionFactory->create();
        $this->sessionFactory = $sessionFactory;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * @return array
     */
    public function getData()
    {
        $customerSession = $this->sessionFactory->create();
        $data = $customerSession->getTemplateFormData();

        if (!empty($data)) {
            $customerId = $data['customer']['entity_id'] ?? null;
            $this->loadedData[$customerId] = $data;
            $customerSession->unsTemplateFormData();
        }

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $template) {
            $this->loadedData[$template->getEntityId()] = $template->getData();
        }

        return $this->loadedData;
    }
}
