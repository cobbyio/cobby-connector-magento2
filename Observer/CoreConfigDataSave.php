<?php
namespace Cobby\Connector\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class CoreConfigDataSave
 * @package Cobby\Connector\Observer
 */
class CoreConfigDataSave implements ObserverInterface
{
    /**
     * @var \Cobby\Connector\Helper\Queue
     */
    private $queue;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $config;

    /**
     * @var \Cobby\Connector\Model\Product
     */
    private $productFactory;

    /**
     * CoreConfigDataSave constructor.
     * @param \Cobby\Connector\Model\ProductFactory $productFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     */
    public function __construct(
        \Cobby\Connector\Model\ProductFactory $productFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $config

    ){
        $this->productFactory = $productFactory->create();
        $this->config = $config;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
//        $data = $observer->getEvent();
//        //$data = $data->getDataByPath();
//
//        $newScope = $data->getPriceScope();
//
//        $currentScope = $this->config->getValue(\Magento\Store\Model\Store::XML_PATH_PRICE_SCOPE);
//
//        if($newScope != $currentScope){
//
//            $this->productFactory->resetHash('config_price_changed');
//            return;
//        }
        return;
    }
}