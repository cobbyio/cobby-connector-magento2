<?php

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