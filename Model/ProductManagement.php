<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;


class ProductManagement implements \Cobby\Connector\Api\ProductManagementInterface
{

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceModel;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Json Helper
     *
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var Product
     */
    private $product;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * ProductManagement constructor.
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\ResourceConnection $resourceModel
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param Product $product
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\ResourceConnection $resourceModel,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Cobby\Connector\Model\Product $product
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productFactory = $productFactory;
        $this->registry = $registry;
        $this->resourceModel = $resourceModel;
        $this->eventManager = $eventManager;
        $this->product = $product;
    }

    public function getList($pageNum, $pageSize)
    {
        $this->eventManager->dispatch('cobby_catalog_product_export_ids_before');

        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();

        $items = $collection
            ->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID)
            ->setPage($pageNum, $pageSize)
            ->load();

        $result =  $items->toArray(array('entity_id', 'sku', 'type_id'));

        $this->eventManager->dispatch('cobby_catalog_product_export_ids_after');

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function updateSkus($jsonData)
    {
        $this->registry->register('is_cobby_import', 1);

        $rows = $this->jsonHelper->jsonDecode($jsonData);

        $result = array();
        
        foreach($rows as $row) {
            $productId = $row['product_id'];
            $sku = $row['sku'];
            $changed = false;

            if (!empty($sku)) {
                $product = $this->productFactory->create();
                $product->setData('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID);
                $product->load($productId);

                if ($product->getSku() != null && $product->getSku() !== $sku) {
                    $product->setSku($sku);
                    $product->save();
                    $changed = true;
                }
            }
            $result[] = array('product_id' => $productId, 'sku'  => $sku, 'changed' => $changed);
        }

        return $result;
    }

    public function updateWebsites($jsonData)
    {
        $this->registry->register('is_cobby_import', 1);

        $websiteData = $this->jsonHelper->jsonDecode($jsonData);

        $result = array();

        if ($websiteData) {
            $tableName = $this->resourceModel->getTableName('catalog_product_website');
            $connection = $this->resourceModel->getConnection();

            //TODO: check for existing product ids
            //TODO: check for existing website_ids
            $productIds = array_keys($websiteData);

            foreach ($websiteData as $productId => $websites) {
                $item = array(
                    'product_id'    => $productId,
                    'added'         => array(),
                    'removed'       => array()
                );

                if ($websites['add']) {
                    foreach ($websites['add'] as $websiteId) {
                        $websitesData[] = array(
                            'product_id'    => $productId,
                            'website_id'    => $websiteId
                        );
                    }

                    $connection->insertOnDuplicate($tableName, $websitesData);

                    $item['added'] = $websites['add'];
                }
                if ($websites['remove']) {
                    $connection->delete(
                        $tableName,
                        array($connection->quoteInto('product_id = ?', $productId),
                            $connection->quoteInto('website_id IN (?)', $websites['remove'])
                        )
                    );

                    $item['removed'] = $websites['remove'];
                }
                $result[] = $item;
            }
        }

        $this->product->updateHash($productIds);

        return $result;
    }
}
