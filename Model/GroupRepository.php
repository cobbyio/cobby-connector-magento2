<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;

class GroupRepository implements \Cobby\Connector\Api\GroupRepositoryInterface
{
    /**
     * @var \Magento\Store\Model\ResourceModel\Group\CollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * @param  \Magento\Store\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory
     */
    public function __construct(
        \Magento\Store\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory
    )
    {
        $this->groupCollectionFactory = $groupCollectionFactory;
    }

    public function getList()
    {
        $result = array();
        /** @var \Magento\Store\Model\ResourceModel\Group\Collection $groupCollection */
        $groupCollection = $this->groupCollectionFactory->create();
        $groupCollection->setLoadDefault(true);

        foreach ($groupCollection as $item) {
            $result[] = array(
                'group_id' => $item->getGroupId(),
                'default_store_id' => $item->getDefaultStoreId(),
                'root_category_id' => $item->getRootCategoryId()
            );
        }
        return $result;
    }
}
