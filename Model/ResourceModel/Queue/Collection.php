<?php
namespace Cobby\Connector\Model\ResourceModel\Queue;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Cobby\Connector\Model\Queue', 'Cobby\Connector\Model\ResourceModel\Queue');
    }

    /**
     * @param int $minQueueId
     * @return Collection
     */
    public function addMinQueueIdFilter($minQueueId)
    {
        $this->addFieldToFilter($this->getResource()->getIdFieldName(), array('gteq' => $minQueueId));

        return $this;
    }

    /**
     * Deletes all table rows
     *
     * @return int The number of affected rows.
     */
    public function delete()
    {
        return $this->getConnection()->delete($this->getMainTable());
    }
}