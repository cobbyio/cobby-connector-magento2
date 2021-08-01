<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Plugin;

/**
 * Class AbstractPlugin
 * @package Cobby\Connector\Model\Plugin
 */
abstract class AbstractPlugin
{
    /**
     * @var \Cobby\Connector\Helper\Queue
     */
    private $queueHelper;

    /**
     * @var \Cobby\Connector\Model\Product
     */
    private $productModel;

    /**
     * constructor.
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

    /**
     * save changes to queue and notifiy cobby service
     *
     * @param $entity
     * @param $ids
     * @param $action
     */
    public function enqueueAndNotify($entity, $action, $ids)
    {
        $this->queueHelper->enqueueAndNotify($entity, $action, $ids);
    }

    /**
     * @param $prefix
     */
    public function resetHash($prefix){
        $this->productModel->resetHash($prefix);
    }

    /**
     * @param $ids
     */
    public function updateHash($ids){
        $this->productModel->updateHash($ids);
    }
}
