<?php
namespace Cobby\Connector\Model\ResourceModel;

class Queue extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('cobby_connector_queue', 'queue_id');
    }
}