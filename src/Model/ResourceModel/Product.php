<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\ResourceModel;


class Product extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function _construct()
    {
        $this->_init('cobby_connector_product', 'entity_id');
    }

    public function resetHash($hash)
    {
        $this->getConnection()->update($this->getMainTable(), array('hash' => $hash));
        return $this;
    }

    public function updateHash($ids, $hash)
    {
        $select = $this->getConnection()
            ->select()
            ->from(array('cp' => $this->getTable('catalog_product_entity')), array('entity_id', new \Zend_Db_Expr('"'. $hash . '" as hash')))
            ->where('cp.entity_id IN (?)', $ids)
            ->insertFromSelect($this->getMainTable(), array('entity_id', 'hash'), \Magento\Framework\DB\Adapter\AdapterInterface::INSERT_ON_DUPLICATE);

        $this->getConnection()->query($select);
        return $this;
    }
}