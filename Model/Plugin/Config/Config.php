<?php
namespace Cobby\Connector\Model\Plugin\Config;


/**
 * Class Config
 * @package Cobby\Connector\Model\Plugin\Config
 */
/**
 * Class Config
 * @package Cobby\Connector\Model\Plugin\Config
 */
class Config extends \Cobby\Connector\Model\Plugin\AbstractPlugin
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $config;

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Cobby\Connector\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Cobby\Connector\Helper\Queue $queueHelper,
        \Cobby\Connector\Model\ProductFactory $productFactory
    ){
        $this->config = $config;
        parent::__construct($queueHelper, $productFactory);

    }

    /**
     * @param \Magento\Config\Model\Config $subject
     * @param callable $proceed
     * @return mixed
     */
    public function aroundSave(
    \Magento\Config\Model\Config $subject, callable $proceed

    )
    {
        $currentScope = $this->config->getValue(\Magento\Store\Model\Store::XML_PATH_PRICE_SCOPE);
        $newScope = $subject->getData('groups/price/fields/scope/value');

        if ($newScope != $currentScope){
            $this->resetHash('config_price_changed');
        }


        $returnValue = $proceed();

        return $returnValue;
    }
}