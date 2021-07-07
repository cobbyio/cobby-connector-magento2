<?php
namespace Cobby\Connector\Model\ResourceModel\Product;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('Cobby\Connector\Model\Product', 'Cobby\Connector\Model\ResourceModel\Product');
    }

}