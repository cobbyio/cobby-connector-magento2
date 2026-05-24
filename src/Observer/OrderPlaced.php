<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class OrderPlaced
 * @package Cobby\Connector\Observer
 */
class OrderPlaced implements ObserverInterface
{
    /**
     * @var \Cobby\Connector\Helper\Queue
     */
    private $queueHelper;

    /**
     * @var \Cobby\Connector\Model\ProductFactory
     */
    private $productModel;

    /**
     * OrderPlaced constructor.
     * @param \Cobby\Connector\Helper\Queue $queueHelper
     * @param \Cobby\Connector\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Cobby\Connector\Helper\Queue $queueHelper,
        \Cobby\Connector\Model\ProductFactory $productFactory
    ){
        $this->queueHelper = $queueHelper;
        $this->productModel = $productFactory->create();
    }

    public function execute(\Magento\Framework\Event\Observer $observer){
        $data = $observer->getEvent()->getOrder()->getData();

        $ids = array();

        if (isset($data['items'])) {
            foreach ($data['items'] as $item){
                $ids[] = $item->getData('product_id');
            }
            $this->queueHelper->enqueueAndNotify('product', 'save', $ids);
            $this->productModel->updateHash($ids);
        }

        return;
    }
}