<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

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