<?php
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