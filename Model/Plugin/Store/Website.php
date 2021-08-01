<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Plugin\Store;

class Website extends \Cobby\Connector\Model\Plugin\AbstractPlugin
{
    public function aroundSave(
        \Magento\Store\Model\ResourceModel\Website $websiteResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $website
    ) {
        $websiteResource->addCommitCallback(function () use ($website) {
           $this->resetHash('website_changed');
        });

        return $proceed($website);
    }

    public function aroundDelete(
        \Magento\Store\Model\ResourceModel\Website $websiteResource,
        \Closure $proceed,
        \Magento\Framework\Model\AbstractModel $website
    ){
        $this->resetHash('website_changed');

        return $proceed($website);
    }

}