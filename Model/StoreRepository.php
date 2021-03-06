<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;

class StoreRepository implements \Cobby\Connector\Api\StoreRepositoryInterface
{
    /**
     * @var \Magento\Store\Model\ResourceModel\Store\CollectionFactory
     */
    protected $storeCollectionFactory;

    /**
     * @param \Magento\Store\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory
     */
    public function __construct(
        \Magento\Store\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory
    )
    {
        $this->storeCollectionFactory = $storeCollectionFactory;
    }

    public function getList()
    {
        $result = array();
        /** @var $storeCollection \Magento\Store\Model\ResourceModel\Store\Collection */
        $storeCollection = $this->storeCollectionFactory->create();
        $storeCollection->setLoadDefault(true);

        $sortOrder = 0;
        foreach ($storeCollection as $store) {
            $result[] = array(
                'store_id'    => $store->getId(),
                'code'        => $store->getCode(),
                'group_id'    => $store->getGroupId(),
                'website_id'  => $store->getWebsiteId(),
                'name'        => $store->getName(),
                'is_active'   => $store->getIsActive(),
                'sort_order'  => $sortOrder,
                'locale'      => $store->getConfig('general/locale/code')
            );
            $sortOrder++;
        }
        return $result;
    }
}
