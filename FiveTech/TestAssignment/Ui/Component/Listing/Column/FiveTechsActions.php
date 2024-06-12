<?php

declare(strict_types=1);

namespace FiveTech\TestAssignment\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class FiveTechsActions for edit and delete record
 */
class FiveTechsActions extends Column
{

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * Edit action path
     */
    const URL_PATH_EDIT = 'five_tech/record/edit';

    /**
     * Delete action path
     */
    const URL_PATH_DELETE = 'five_tech/record/delete';


    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface       $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'entity_id' => $item['entity_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'entity_id' => $item['entity_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete ' . $item['entity_id']),
                                'message' => __('Are you sure you wan\'t to delete a record of ' . $item['entity_id'] . ' ?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
