<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;

class WebsiteRepository implements \Cobby\Connector\Api\WebsiteRepositoryInterface
{
    /**
     * @var \Magento\Store\Model\ResourceModel\Website\CollectionFactory
     */
    protected $websiteCollectionFactory;

    /**
     * @param \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollectionFactory
     */
    public function __construct(
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollectionFactory
    )
    {
        $this->websiteCollectionFactory = $websiteCollectionFactory;
    }

    public function getList()
    {
        $result = array();

        $collection = $this->websiteCollectionFactory->create();
        $collection->setLoadDefault(true);

        $sortOrder = 0;
        foreach ($collection as $website) {
            $result[] = array(
                'website_id'        => $website->getWebsiteId(),
                'code'              => $website->getCode(),
                'name'              => $website->getName(),
                'default_group_id'  => $website->getDefaultGroupId(),
                'is_default'        => $website->getIsDefault(),
                'sort_order'        => $sortOrder //$website->getSortOrder()
            );
            $sortOrder++;
        }

        return $result;
    }
}
