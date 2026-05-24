<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Helper;

class CobbyApi extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * cobby service url
     */
	const COBBY_API = 'https://api.cobby.io'; 

    /**
     * @var \Cobby\Connector\Helper\Settings
     */
    private $settings;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $curl;

    /**
     * @var \Magento\Framework\App\ProductMetadata
     */
    private $productMetadata;

    /**
     * constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Cobby\Connector\Helper\Settings $settings
     * @param \Magento\Framework\HTTP\Client\Curl $curl,
     * @param \Magento\Framework\App\ProductMetadata $productMetadata
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Cobby\Connector\Helper\Settings $settings,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Framework\App\ProductMetadata $productMetadata
    ) {
        parent::__construct($context);
        $this->settings = $settings;
        $this->curl = $curl;
        $this->productMetadata = $productMetadata;
    }

    /**
     * Create a cobby request with required items
     *
     * @return array
     */
    private function createCobbyRequest()
    {
        $result = array();
        $result['LicenseKey']   = $this->settings->getLicenseKey();
        $result['ShopUrl']      = $this->settings->getDefaultBaseUrl();
        $result['CobbyVersion'] = $this->settings->getCobbyVersion();

        return $result;
    }

    /**
     *
     * Performs an HTTP POST request to cobby service
     *
     * @param $method
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function restPost($method, array $data)
    {
        $apiUrl = self::COBBY_API . '/' . $method;
        $this->curl->post($apiUrl, $data);
        $this->curl->setOptions([
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_TIMEOUT => 60
        ]);

        if ($this->curl->getStatus() != 200 && $this->curl->getStatus() != 201) {
            $errorRestResultAsObject = json_decode($this->curl->getBody());
            if ($errorRestResultAsObject != null) { // check if response is right
                throw new \Exception($errorRestResultAsObject->message);
            }
        }
        return json_decode($this->curl->getBody());
    }

    /**
     * Notify cobby about magento changes
     *
     * @param $objectType
     * @param $method
     * @param $objectIds
     */
    public function notifyCobbyService($objectType, $method, $objectIds)
    {
        $request = $this->createCobbyRequest();
        if ($request['LicenseKey'] != '') {
            $request['ObjectType'] = $objectType;
            $request['ObjectId'] = $objectIds;
            $request['Method'] = $method;

            try {
                $this->restPost('magento/notify', $request);
            } catch (\Exception $e) { // Zend_Http_Client_Adapter_Exception
                $this->_logger->info($e);
            }
        }
    }
}