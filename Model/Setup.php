<?php
namespace Cobby\Connector\Model;

use Cobby\Connector\Api\SetupInterface;
use Cobby\Connector\Helper\Settings;

/**
 * Description TODO
 */
class Setup implements SetupInterface
{
    /**
     * Description TODO
     * @var Settings
     */
    private $settingsHelper;
    /**
     * @var Magento\Framework\App\Config\ReinitableConfigInterface
     */
    private $reinitableConfig;

    /**
     * Setup constructor.
     * @param Settings $settingsHelper
     * @param \Magento\Framework\Cache\FrontendInterface $cache
     */
    public function __construct(
        Settings $settingsHelper,
        \Magento\Framework\App\Config\ReinitableConfigInterface $reinitableConfig
    ) {
        $this->settingsHelper = $settingsHelper;
        $this->reinitableConfig = $reinitableConfig;
    }

    /**
     * Description TODO
     * @return mixed
     */
    public function export()
    {
        $result = array();
        $license = $this->settingsHelper->getLicenseKey();
        $cobbyVersion = $this->settingsHelper->getCobbyVersion();
        $cobbyActive = $this->settingsHelper->getCobbyActive();

        try{
            $memory = ini_get('memory_limit');
        }
        catch(\Exception $e){
            $memory = $e->getMessage();
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
        $magentoVersion = $productMetadata->getVersion(); //will return the magento version

        $result[] = array(
            "license_key" => $license,
            "cobby_version" => $cobbyVersion,
            "magento_version" => $magentoVersion,
            'cobby_active' => $cobbyActive,
            'memory' => $memory);
        return $result;
    }

    /**
     * Description TODO
     * @param string $licenseKey
     * @param bool $clearCache
     * @return bool
     */
    public function import($licenseKey, $clearCache)
    {
        $success = $this->settingsHelper->setLicenseKey($licenseKey);
        if($clearCache){
            $this->reinitableConfig->reinit();
        }

        return true;
    }
}
