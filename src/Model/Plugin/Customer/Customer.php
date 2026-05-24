<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Plugin\Customer;

class Customer extends \Cobby\Connector\Model\Plugin\AbstractPlugin
{

    public function aroundSave(
        \Magento\Customer\Model\ResourceModel\Group $groupResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $group
    ) {
        $groupResource->addCommitCallback(function () use ($group) {
            $this->enqueueAndNotify('customer_group', 'save', $group->getId());
        });
        return $proceed($group);
    }

    public function aroundDelete(
        \Magento\Customer\Model\ResourceModel\Group $groupResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $group
    ) {
        $this->enqueueAndNotify('customer_group', 'delete', $group->getId());
        return $proceed($group);
    }
}