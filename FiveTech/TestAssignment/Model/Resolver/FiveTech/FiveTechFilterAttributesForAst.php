<?php

namespace FiveTech\TestAssignment\Model\Resolver\FiveTech;

use Magento\Framework\GraphQl\ConfigInterface;
use Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesInterface;

/**
 * Retrieve filterable attributes for Finder queries
 */
class FiveTechFilterAttributesForAst implements FieldEntityAttributesInterface
{
    /**
     * Map schema fields to entity attributes
     *
     * @var array
     */
    private $fieldMapping = [
        // Lets suppose if required 'dropdown_id' => 'entity_id'
    ];

    /**
     * @var array
     */
    private $additionalFields = [
        //  'is_enabled'
    ];

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param ConfigInterface $config
     * @param array $additionalFields
     * @param array $attributeFieldMapping
     */
    public function __construct(
        ConfigInterface $config,
        array           $additionalFields = [],
        array           $attributeFieldMapping = []
    )
    {
        $this->config = $config;
        $this->additionalFields = array_merge($this->additionalFields, $additionalFields);
        $this->fieldMapping = array_merge($this->fieldMapping, $attributeFieldMapping);
    }

    /**
     * @inheritdoc
     *
     * Gather attributes for Transaction filtering
     * Example format ['attributeNameInGraphQl' => ['type' => 'String'. 'fieldName' => 'attributeNameInSearchCriteria']]
     *
     * @return array
     */
    public function getEntityAttributes(): array
    {
        $transactionFilterType = $this->config->getConfigElement('FiveTechFilterInput');

        if (!$transactionFilterType) {
            throw new \LogicException(__("FiveTechFilterInput type not defined in schema."));
        }

        $fields = [];
        foreach ($transactionFilterType->getFields() as $field) {
            $fields[$field->getName()] = [
                'type' => 'String',
                'fieldName' => $this->fieldMapping[$field->getName()] ?? $field->getName(),
            ];
        }

        foreach ($this->additionalFields as $additionalField) {
            $fields[$additionalField] = [
                'type' => 'String',
                'fieldName' => $additionalField,
            ];
        }

        return $fields;
    }
}
