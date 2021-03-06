<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;


class Debug implements \Cobby\Connector\Api\DebugInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Debug constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ){
        $this->storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function export()
    {
        $result = array();

        foreach ($this->storeManager->getStores(true) as $store) {
            foreach ($this->productCollectionFactory->create()->getProductTypeIds() as $productType) {
                $typeCount[$productType] =  $this->productCollectionFactory
                    ->create()
                    ->addStoreFilter($store->getId())
                    ->addAttributeToFilter('type_id', $productType)
                    ->getSize();
            }

            $result[] = array(
                "store_id"              => $store->getId(),
                "store_name"            => $store->getName(),
                "store_code"            => $store->getCode(),
                "website_id"            => $store->getWebsite()->getId(),
                "website_name"          => $store->getWebsite()->getName(),
                "product_count"         => array_sum($typeCount),
                "product_types"         => $typeCount);
        }

        return $result;
    }
}