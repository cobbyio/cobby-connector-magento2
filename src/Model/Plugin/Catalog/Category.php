<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Plugin\Catalog;

class Category extends \Cobby\Connector\Model\Plugin\AbstractPlugin
{
    public function aroundSave(
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $category
    ) {
        $categoryResource->addCommitCallback(function () use ($category) {
            $this->enqueueAndNotify('category', 'save', $category->getId());

            $affectedProductIds = $category->getAffectedProductIds();
            if ($affectedProductIds) {
                $this->updateHash($affectedProductIds);
                $this->enqueueAndNotify('product', 'save', $affectedProductIds);
            }
        });

        return $proceed($category);
    }

    public function aroundDelete(
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $category
    ) {
        $this->enqueueAndNotify('category', 'delete', $category->getId());
        return $proceed($category);
    }
}