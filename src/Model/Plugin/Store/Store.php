<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Plugin\Store;


class Store extends \Cobby\Connector\Model\Plugin\AbstractPlugin
{
    public function aroundSave(
        \Magento\Store\Model\ResourceModel\Store $storeResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $store
    ) {
        $storeResource->addCommitCallback(function () use ($store) {
            $this->resetHash('store_changed');
        });

        return $proceed($store);
    }

    public function aroundDelete(
        \Magento\Store\Model\ResourceModel\Store $storeResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $store
    ){
        $this->resetHash('store_changed');

        return $proceed($store);
    }
}