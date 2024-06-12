<?php

declare(strict_types=1);

namespace FiveTech\TestAssignment\Api\Data;

interface FiveTechInterface
{

    const ENTITY_ID = 'entity_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const COUNTRY = 'country';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get entityId
     * @return int
     */
    public function getEntityId();

    /**
     * Set entityId
     * @param int $entityId
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechInterface
     */
    public function setEntityId(int $entityId);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechInterface
     */
    public function setName($name);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set name
     * @param string $description
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechInterface
     */
    public function setDescription($description);

    /**
     * Get country
     * @return string|null
     */
    public function getCountry();

    /**
     * Set name
     * @param string $country
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechInterface
     */
    public function setCountry($country);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \FiveTech\TestAssignment\Api\Data\FiveTechInterface
     */
    public function setUpdatedAt($updatedAt);
}
