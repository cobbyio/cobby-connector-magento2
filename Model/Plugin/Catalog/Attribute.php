<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Plugin\Catalog;

class Attribute extends \Cobby\Connector\Model\Plugin\AbstractPlugin
{

    public function aroundSave(
        \Magento\Catalog\Model\ResourceModel\Attribute $attributeResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $attribute
    ) {
        $attributeResource->addCommitCallback(function () use ($attribute) {
            $this->enqueueAndNotify('attribute', 'save', $attribute->getId());
        });
        return $proceed($attribute);
    }

    public function aroundDelete(
        \Magento\Catalog\Model\ResourceModel\Attribute $attributeResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $attribute
    ) {
        $this->enqueueAndNotify('attribute', 'delete', $attribute->getId());
        return $proceed($attribute);
    }

}