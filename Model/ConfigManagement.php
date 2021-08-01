<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;


/**
 * Class ConfigManagement
 * @package Cobby\Connector\Model
 */
class ConfigManagement implements \Cobby\Connector\Api\ConfigManagementInterface
{
    const EE = 'Enterprise';

    /**
     * config paths use in cobby
     *
     * @var array
     */
    protected $_configPaths = [
        'cobby/settings/overwrite_images',
        'cobby/settings/cobby_version',
        'cobby/settings/clear_cache',
        'web/unsecure/base_media_url',
		'cataloginventory/item_options/manage_stock',
        'cataloginventory/item_options/backorders',
        'cataloginventory/item_options/min_qty',
        \Cobby\Connector\Helper\Settings::XML_PATH_COBBY_SETTINGS_MANAGE_STOCK
    ];

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $backendUrl;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    private $systemCheckHelper;
    private $settings;

    private $jsonHelper;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Cobby\Connector\Helper\Settings $settings
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Cobby\Connector\Helper\Systemcheck $systemCheckHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Cobby\Connector\Helper\Settings $settings
    ) {
        $this->jsonHelper       = $jsonHelper;
        $this->scopeConfig      = $scopeConfig;
        $this->storeManager     = $storeManager;
        $this->backendUrl       = $backendUrl;
        $this->productMetadata  = $productMetadata;
        $this->systemCheckHelper = $systemCheckHelper;
        $this->settings         = $settings;

    }

    public function getList()
    {
        $result = array();
        $isEE = $this->productMetadata->getEdition() == self::EE;
        $magentoVersion = $this->productMetadata->getVersion();
        $adminUrl = $this->backendUrl->turnOffSecretKey()->getUrl('adminhtml');

        foreach ($this->storeManager->getStores(true) as $store) {
            $baseUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);

            $storeConfigs = array(
                'store_id' => $store->getId(),
                'web/unsecure/base_url' => $baseUrl,
                'cobby/settings/admin_url' => $adminUrl,
                'mage/core/enterprise' => $isEE,
                'mage/core/magento_version' => $magentoVersion
            );

            foreach($this->_configPaths as $path)
            {
                $storeConfigs[$path] = $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId());
            }
            $storeConfigs['web/unsecure/base_media_url'] = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            
            $result[] = $storeConfigs;
        }

        return $result;
    }

    public function active($jsonData)
    {
        $result = array();
        $data = $this->jsonHelper->jsonDecode($jsonData);
        $value = 0;

        if ($data['active'] == "true") {
            $value = 1;
        }

        $this->settings->setCobbyActive($value);

        $result[] = $data;

        return $result;
    }

    public function getReport()
    {
        $result = array();

        $testResults = $this->systemCheckHelper->getTestResults();

        foreach ($testResults as $test => $testResult) {
            $prepare = array(
              'test' => $test,
              'value' => $testResult['value'],
              'code' => $testResult['code']
            );

            $result[] = $prepare;
        }

        return $result;
    }
}
