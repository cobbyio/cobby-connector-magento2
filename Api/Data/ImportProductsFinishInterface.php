<?php

namespace Cobby\Connector\Api\Data;

/**
 * Interface ImportProductsFinishInterface
 * @api
 */
interface ImportProductsFinishInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**#@+
     * Constants defined for parameters event_type
     * and entities
     */
    const ENTITIES = 'entities';

    /**
     * @return \Cobby\Connector\Api\Data\ImportProductsFinishEntityInterface[]
     */
    public function getEntities();

    /**
     * @param \Cobby\Connector\Api\Data\ImportProductsFinishEntityInterface[] $entities
     * @return $this
     */
    public function setEntities(array $entities);
}



