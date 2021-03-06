<?php
/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */
namespace Cobby\Connector\Helper;

use Magento\Config\Model\ResourceModel\Config;

class Settings extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_PRODUCT_CATEGORY_POSITION    = 'cobby/settings/product_category_position';
    const XML_PATH_LICENSE_KEY                  = 'cobby/settings/license_key';
    const XML_PATH_COBBY_VERSION                = 'cobby/settings/cobby_version';
    const XML_PATH_COBBY_DBVERSION                = 'cobby/settings/cobby_dbversion';
    const XML_PATH_COBBY_SETTINGS_CONTACT_EMAIL = 'cobby/settings/contact_email';
    const XML_PATH_COBBY_SETTINGS_MANAGE_STOCK  = 'cobby/stock/manage';
    const XML_PATH_COBBY_SETTINGS_AVAILABILITY  = 'cobby/stock/availability';
    const XML_PATH_COBBY_SETTINGS_QUANTITY      = 'cobby/stock/quantity';
    const XML_PATH_COBBY_SETTINGS_ACTIVE        = 'cobby/settings/active';
    const XML_PATH_COBBY_URL                    = 'cobby/settings/base_url';
    const MANAGE_STOCK_ENABLED                  = 0;
    const MANAGE_STOCK_READONLY                 = 1;
    const MANAGE_STOCK_DISABLED                 = 2;
    const MODULE_ENABLED                        = 0;
    const MODULE_DISABLED                       = 1;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    protected $config;

    /**
     * constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param  \Magento\Framework\Encryption\EncryptorInterface $encryptor
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        Config $config
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->encryptor = $encryptor;
        $this->config = $config;
    }

    /**
     * get default product category position
     *
     * @return int
     */
    public function getProductCategoryPosition()
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_PRODUCT_CATEGORY_POSITION);
    }

    /**
     *  Get current license Key
     *
     * @return string
     */
    public function getLicenseKey()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LICENSE_KEY);
    }

    /**
     * Description TODO
     * @param $licenseKey
     * @return bool
     */
    public function setLicenseKey($licenseKey)
    {
        $this->config->saveConfig(self::XML_PATH_LICENSE_KEY,
            $licenseKey,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0);

        return true;
    }
    
    /**
     * Get admin base url
     *
     * @return string
     */
    public function getDefaultBaseUrl()
    {
        return $this->storeManager
            ->getStore(\Magento\Store\Model\Store::DEFAULT_STORE_ID)
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB, ['_secure' => true]);
    }

    public function setCobbyUrl($url)
    {
        $this->config->saveConfig(self::XML_PATH_COBBY_URL,
            $url,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0);
    }

    public function getCobbyUrl()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_COBBY_URL);
    }

    public function setCobbyVersion()
    {
        $this->config->saveConfig(self::XML_PATH_COBBY_DBVERSION,
            $this->scopeConfig->getValue(self::XML_PATH_COBBY_VERSION),
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0);
    }

    /**
     * Get current cobby version
     *
     * @return string
     */
    public function getCobbyVersion()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_COBBY_VERSION);
    }

    public function isCobbyEnabled()
    {
        $enabled = $this->getCobbyActive();
        $license = $this->getLicenseKey();

        if ($enabled && isset($license)) {
            return true;
        } else {
            return false;
        }
    }

    public function setCobbyActive($value)
    {
        $this->config->saveConfig(self::XML_PATH_COBBY_SETTINGS_ACTIVE,
            $value,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0);
        return true;
    }

    public function getCobbyActive()
    {
        $status = $this->scopeConfig->getValue(self::XML_PATH_COBBY_SETTINGS_ACTIVE);

        if($status === "0") {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Get setting for stock management
     *
     * @return integer
     */
    public function getManageStock()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_COBBY_SETTINGS_MANAGE_STOCK);
    }

    /**
     * Get default setting for availability
     *
     * @return integer
     */
    public function getDefaultAvailability()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_COBBY_SETTINGS_AVAILABILITY);
    }

    /**
     * Get default value for quantity
     *
     * @return integer
     */
    public function getDefaultQuantity()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_COBBY_SETTINGS_QUANTITY);
    }

    /**
     * Retrieve rename images
     *
     * @return string
     */
    public function getOverwriteImages()
    {
        return $this->scopeConfig->isSetFlag('cobby/settings/overwrite_images');
    }
}
