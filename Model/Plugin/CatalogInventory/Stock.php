<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Plugin\CatalogInventory;

/**
 * Class Stock
 * @package Cobby\Connector\Model\Plugin\CatalogInventory
 */
class Stock extends \Cobby\Connector\Model\Plugin\AbstractPlugin
{

    public function aroundSave(
        \Magento\CatalogInventory\Model\ResourceModel\Stock\Item $stockResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $stock

    ) {
        $stockData = $stock->getQuote();

        $this->enqueueAndNotify('stock', 'save', $stock->getProductId());
        $this->updateHash($stock->getProductId());

        return $proceed($stock);
    }
}