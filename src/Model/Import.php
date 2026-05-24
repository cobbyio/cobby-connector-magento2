<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;

class Import implements \Cobby\Connector\Api\ImportInterface
{
    const START = 'start';
    const FINISH = 'finish';

    /**
     * Json Helper
     *
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Cobby\Connector\Api\ImportProductLinkManagementInterface
     */
    private $importProductLink;

    /**
     * @var \Cobby\Connector\Api\ImportProductCategoryManagementInterface
     */
    private $importProductCategory;

    /**
     * @var \Cobby\Connector\Api\ImportProductTierPriceManagementInterface
     */
    private $importProductTierPrice;

    /**
     * @var \Cobby\Connector\Api\ImportProductManagementInterface
     */
    private $importProduct;

    /**
     * @var \Cobby\Connector\Api\ImportProductStockManagementInterface
     */
    private $importProductStock;

    /**
     * @var \Cobby\Connector\Api\ImportProductImageManagementInterface
     */
    private $importProductImage;

    /**
     * @var \Cobby\Connector\Api\ImportProductGroupedManagementInterface
     */
    private $importProductGrouped;

    /**
     * @var \Cobby\Connector\Api\ImportProductConfigurableManagementInterface
     */
    private $importProductConfigurable;

    /**
     * @var \Cobby\Connector\Api\ImportProductCustomOptionManagementInterface
     */
    private $importProductCustomOption;

    /**
     * @var \Cobby\Connector\Api\ImportProductUrlManagementInterface
     */
    private $importProductUrl;

    /**
     * @var \Magento\ImportExport\Model\ImportFactory
     */
    private $importModelFactory;

    /**
     * @var \Cobby\Connector\Api\ImportProductBundleManagementInterface
     */
    private $importProductBundle;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;

    /**
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Cobby\Connector\Api\ImportProductLinkManagementInterface $importProductLink
     * @param \Cobby\Connector\Api\ImportProductCategoryManagementInterface $importProductCategory
     * @param \Cobby\Connector\Api\ImportProductTierPriceManagementInterface $importProductTierPrice
     * @param \Cobby\Connector\Api\ImportProductManagementInterface $importProduct
     * @param \Cobby\Connector\Api\ImportProductStockManagementInterface $importProductStock
     * @param \Cobby\Connector\Api\ImportProductImageManagementInterface $importProductImage
     * @param \Cobby\Connector\Api\ImportProductGroupedManagementInterface $importProductGrouped
     * @param \Cobby\Connector\Api\ImportProductConfigurableManagementInterface $importProductConfigurable
     * @param \Cobby\Connector\Api\ImportProductCustomOptionManagementInterface $importProductCustomOption
     * @param \Cobby\Connector\Api\ImportProductUrlManagementInterface $importProductUrl
     * @param \Magento\ImportExport\Model\ImportFactory $importModelFactory
     * @param \Cobby\Connector\Api\ImportProductBundleManagementInterface $importProductBundle
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Cobby\Connector\Api\ImportProductLinkManagementInterface $importProductLink,
        \Cobby\Connector\Api\ImportProductCategoryManagementInterface $importProductCategory,
        \Cobby\Connector\Api\ImportProductTierPriceManagementInterface $importProductTierPrice,
        \Cobby\Connector\Api\ImportProductManagementInterface $importProduct,
        \Cobby\Connector\Api\ImportProductStockManagementInterface $importProductStock,
        \Cobby\Connector\Api\ImportProductImageManagementInterface $importProductImage,
        \Cobby\Connector\Api\ImportProductGroupedManagementInterface $importProductGrouped,
        \Cobby\Connector\Api\ImportProductConfigurableManagementInterface $importProductConfigurable,
        \Cobby\Connector\Api\ImportProductCustomOptionManagementInterface $importProductCustomOption,
        \Cobby\Connector\Api\ImportProductUrlManagementInterface $importProductUrl,
        \Magento\ImportExport\Model\ImportFactory $importModelFactory,
        \Cobby\Connector\Api\ImportProductBundleManagementInterface $importProductBundle,
        \Magento\Framework\Event\ManagerInterface $eventManager

    ) {
        $this->jsonHelper = $jsonHelper;
        $this->importProductLink = $importProductLink;
        $this->importProductCategory = $importProductCategory;
        $this->importProductTierPrice = $importProductTierPrice;
        $this->importProduct = $importProduct;
        $this->importProductStock = $importProductStock;
        $this->importProductImage = $importProductImage;
        $this->importProductGrouped = $importProductGrouped;
        $this->importProductConfigurable = $importProductConfigurable;
        $this->importProductCustomOption= $importProductCustomOption;
        $this->importProductUrl = $importProductUrl;
        $this->importModelFactory = $importModelFactory;
        $this->importProductBundle = $importProductBundle;
        $this->eventManager = $eventManager;
    }

    /**
     * @inheritdoc
     */
    public function importProducts($jsonData, $transactionId)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProduct->import($rows, $transactionId);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function importProductLinks($jsonData)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProductLink->import($rows);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function importProductCategories($jsonData)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProductCategory->import($rows);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function importProductTierPrices($jsonData)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProductTierPrice->import($rows);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function importProductStocks($jsonData)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProductStock->import($rows);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function importProductImages($jsonData)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProductImage->import($rows);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function importProductGrouped($jsonData)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProductGrouped->import($rows);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function importProductConfigurable($jsonData)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProductConfigurable->import($rows);
        return true;
    }

    /**
     * @param string $jsonData
     * @return bool|mixed
     * @inheritdoc
     */
    public function importProductCustomOption($jsonData)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $this->importProductCustomOption->import($rows);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function importProductUrls($jsonData)
    {
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProductUrl->import($rows);
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function importProductBundle($jsonData){
        $rows = $this->jsonHelper->jsonDecode($jsonData);
        $result = $this->importProductBundle->import($rows);
        return $result;
    }

    public function importProductsStart()
    {
        $this->eventManager->dispatch('cobby_import_product_started');

        return true;
    }

    /**
     * @param \Cobby\Connector\Api\Data\ImportProductsFinishInterface $data
     * @return bool
     */
    public function importProductsFinish(\Cobby\Connector\Api\Data\ImportProductsFinishInterface $data)
    {
        $this->eventManager->dispatch('cobby_import_product_finished', array(
                'entities'          => $data->getEntities()));

        return true;
    }
}
