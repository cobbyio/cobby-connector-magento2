<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Plugin\Catalog;

/**
 * Class Product
 * @package Cobby\Connector\Model\Plugin\Catalog
 */
class Product extends \Cobby\Connector\Model\Plugin\AbstractPlugin
{
    public function aroundSave(
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $product
    ) {
        $productResource->addCommitCallback(function () use ($product) {
            $this->enqueueAndNotify('product', 'save', $product->getId());
            $this->updateHash($product->getId());
        });
        return $proceed($product);
    }

    public function aroundDelete(
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $product
    ) {
        $this->enqueueAndNotify('product', 'delete', $product->getId());
        $this->updateHash($product->getId());

        return $proceed($product);
    }

    public function aroundUpdateAttributes(
        \Magento\Catalog\Model\Product\Action $subject,
        \Closure $closure,
        array $productIds,
        array $attrData,
        $storeId
    ) {
        $result = $closure($productIds, $attrData, $storeId);

        $this->enqueueAndNotify('product', 'save', array_unique($productIds));
        $this->updateHash($productIds);

        return $result;
    }

    public function aroundUpdateWebsites(
        \Magento\Catalog\Model\Product\Action $subject,
        \Closure $closure,
        array $productIds,
        array $websiteIds,
        $type
    ) {
        $result = $closure($productIds, $websiteIds, $type);

        $this->enqueueAndNotify('product', 'save', array_unique($productIds));
        $this->updateHash($productIds);

        return $result;
    }
}