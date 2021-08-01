<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Data;


class ImportProductsFinish extends \Magento\Framework\Api\AbstractSimpleObject
    implements \Cobby\Connector\Api\Data\ImportProductsFinishInterface
{
    /**
     * @return \Cobby\Connector\Api\Data\ImportProductsFinishEntityInterface[]
     */
    public function getEntities()
    {
        return $this->_get(self::ENTITIES);
    }

    /**
     * @param \Cobby\Connector\Api\Data\ImportProductsFinishEntityInterface[] $entities
     * @return $this
     */
    public function setEntities(array $entities)
    {
        return $this->setData(self::ENTITIES, $entities);
    }
}