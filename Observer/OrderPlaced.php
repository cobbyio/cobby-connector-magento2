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
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * OrderPlaced constructor.
     * @param \Cobby\Connector\Helper\Queue $queueHelper
     * @param \Cobby\Connector\Model\ProductFactory $productFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Cobby\Connector\Helper\Queue $queueHelper,
        \Cobby\Connector\Model\ProductFactory $productFactory,
        \Psr\Log\LoggerInterface $logger
    ){
        $this->queueHelper = $queueHelper;
        $this->productModel = $productFactory->create();
        $this->logger = $logger;
    }

    /**
     * Enqueue all ordered products.
     *
     * Stays on "sales_order_place_after" (fires in Order::place() after the
     * payment, before the save): it is the only event covering every way an
     * order is created (checkout, admin, multishipping, API, importers).
     * Later events like "checkout_submit_all_after" miss plain
     * QuoteManagement::submit() callers. The webhook has short timeouts
     * (see CobbyApi), so it cannot stall the checkout for long.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (!$order) {
            return;
        }

        $ids = [];
        foreach ($order->getAllItems() as $item) {
            $ids[] = $item->getProductId();
        }
        $ids = array_values(array_unique(array_filter($ids)));
        if (empty($ids)) {
            return;
        }

        try {
            $this->queueHelper->enqueueAndNotify('product', 'save', $ids);
            $this->productModel->updateHash($ids);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }
}
