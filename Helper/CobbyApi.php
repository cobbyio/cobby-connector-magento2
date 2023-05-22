<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Helper;

use Laminas\Http\Request;
use Magento\Framework\HTTP\LaminasClientFactory;

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
     * @var LaminasClientFactory
     */
    protected $httpClientFactory;

    /**
     * @var \Magento\Framework\App\ProductMetadata
     */
    private $productMetadata;

    /**
     * constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Cobby\Connector\Helper\Settings $settings
     * @param LaminasClientFactory $httpClientFactory
     * @param \Magento\Framework\App\ProductMetadata $productMetadata
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Cobby\Connector\Helper\Settings $settings,
        LaminasClientFactory $httpClientFactory,
        \Magento\Framework\App\ProductMetadata $productMetadata
    ) {
        parent::__construct($context);
        $this->settings = $settings;
        $this->httpClientFactory = $httpClientFactory;
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
        $httpClient = $this->httpClientFactory->create();
        $httpClient->setUri(self::COBBY_API .'/'. $method);
        $httpClient->setOptions(['maxredirects' => 0, 'timeout' => 60]);
        $httpClient->setMethod(Request::METHOD_POST);
        $httpClient->setParameterPost($data);

        $response = $httpClient->send();

        if (!$response->isSuccess()) {
            $errorRestResultAsObject = json_decode($response->getBody());
            if ($errorRestResultAsObject != null) { // check if response is right
                throw new \Exception($errorRestResultAsObject->message);
            }
        }
        $restResultAsObject = json_decode($response->getBody());

        return $restResultAsObject;
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
            } catch (\Exception $e) {
                $this->_logger->info($e);
            }
        }
    }
}