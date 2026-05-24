<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

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



