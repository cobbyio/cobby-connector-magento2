<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Import\Product;

/**
 * Class StockManagement
 * @package Cobby\Connector\Model\Import\Product
 */
class StockManagement extends AbstractManagement implements \Cobby\Connector\Api\ImportProductStockManagementInterface
{
    /**
     * @var array
     */
    protected $defaultStockData = [
        'manage_stock' => 1,
        'use_config_manage_stock' => 1,
        'qty' => 0,
        'min_qty' => 0,
        'use_config_min_qty' => 1,
        'min_sale_qty' => 1,
        'use_config_min_sale_qty' => 1,
        'max_sale_qty' => 10000,
        'use_config_max_sale_qty' => 1,
        'is_qty_decimal' => 0,
        'backorders' => 0,
        'use_config_backorders' => 1,
        'notify_stock_qty' => 1,
        'use_config_notify_stock_qty' => 1,
        'enable_qty_increments' => 0,
        'use_config_enable_qty_inc' => 1,
        'qty_increments' => 0,
        'use_config_qty_increments' => 1,
        'is_in_stock' => 1,
        'low_stock_date' => null,
        'stock_status_changed_auto' => 0,
        'is_decimal_divided' => 0,
    ];

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    private $stockRegistry;
    /**
     * @var \Magento\CatalogInventory\Api\StockConfigurationInterface
     */
    private $stockConfiguration;
    /**
     * @var \Magento\CatalogInventory\Model\Spi\StockStateProviderInterface
     */
    private $stockStateProvider;

    private $cobbySettings;

    private $commandAppend;

    private $commandDelete;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * StockManagement constructor.
     * @param \Magento\Framework\App\ResourceConnection $resourceModel
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\CatalogInventory\Model\Spi\StockStateProviderInterface $stockStateProvider
     * @param \Cobby\Connector\Helper\Settings $cobbySettings
     * @param \Cobby\Connector\Model\Product $product
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @internal param \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceModel,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Magento\CatalogInventory\Model\Spi\StockStateProviderInterface $stockStateProvider,
        \Cobby\Connector\Helper\Settings $cobbySettings,
        \Cobby\Connector\Model\Product $product,
        \Magento\Framework\Module\Manager $moduleManager
    ) {
        $this->stockRegistry = $stockRegistry;
        $this->stockConfiguration = $stockConfiguration;
        $this->stockStateProvider = $stockStateProvider;
        $this->cobbySettings = $cobbySettings;
        $this->moduleManager = $moduleManager;

        parent::__construct($resourceModel, $productCollectionFactory, $eventManager, $resourceHelper, $product);
    }


    public function import($rows)
    {
        $result = array();
        $productIds = array_keys($rows);
        $this->eventManager->dispatch('cobby_import_product_stock_import_before', array( 'products' => $productIds ));

        $manageStock = $this->cobbySettings->getManageStock();
        $defaultQuantity = $this->cobbySettings->getDefaultQuantity();
        $defaultAvailability = $this->cobbySettings->getDefaultAvailability();

        $existingProductIds = $this->loadExistingProductIds($productIds);

        $entityTable = $this->resourceModel->getTableName('cataloginventory_stock_item');
        $stockItems = array();

        $inventorySourceAppendItems = array();
        $inventorySourceDeleteItems = array();

        $multiSources = $this->moduleManager->isEnabled('Magento_InventoryCatalog');

        foreach ($rows as $row) {
            $sku = $row['sku'];
            unset($row['sku']);
            $productId = $row['product_id'];
            unset($row['product_id']);

            if (!in_array($productId, $existingProductIds)) {
                continue;
            }

            if (!empty($row['inventory_sources']) && $multiSources) {

                foreach( $row['inventory_sources'] as $inventorySource ) {
                    if($inventorySource[self::OBJECT_STATE] == self::DELETED ) {
                        $inventorySourceDeleteItems[] = $inventorySource;
                    } else {
                        $inventorySourceAppendItems[] = $inventorySource;
                    }
                }
            }

            unset($row['inventory_sources']);

            $websiteId = $this->stockConfiguration->getDefaultScopeId();
            $stockData = array(

                'product_id' => $productId,
                'website_id' => $websiteId,
            );

            $stockData['stock_id'] = $this->stockRegistry->getStock($websiteId)->getStockId();

            $stockItemDo = $this->stockRegistry->getStockItem($productId, $websiteId);
            $existStockData = $stockItemDo->getData();

            if ($manageStock == \Cobby\Connector\Helper\Settings::MANAGE_STOCK_ENABLED){
                $stockData = array_merge(
                    $stockData,
                    $this->defaultStockData,
                    array_intersect_key($existStockData, $this->defaultStockData),
                    array_intersect_key($row, $this->defaultStockData)
                );
            } elseif (( $manageStock == \Cobby\Connector\Helper\Settings::MANAGE_STOCK_READONLY ||
                        $manageStock == \Cobby\Connector\Helper\Settings::MANAGE_STOCK_DISABLED) &&
                        !$existStockData){
                $defaultStock = array();

                $defaultStock['qty'] = $defaultQuantity;
                $defaultStock['is_in_stock'] = $defaultAvailability;

                $stockData = array_merge(
                    $stockData,
                    $this->defaultStockData,
                    $defaultStock
                );

                if ($multiSources) {
                    $defaultSource = array(
                        'source_code' => 'default',
                        'quantity' => $defaultQuantity,
                        'status' => $defaultAvailability,
                        'sku'   => $sku
                    );

                    $inventorySourceAppendItems[] = $defaultSource;
                }
            }

            $stockItemDo->setData($stockData);

            $stockItems[] = $stockItemDo->getData();
        }

            // Insert rows
        if (!empty($stockItems)) {
            $this->connection->insertOnDuplicate($entityTable, array_values($stockItems));
            $this->touchProducts($existingProductIds);
        }

        if (!empty($inventorySourceAppendItems)) {
            //"This code needs porting or exist for backward compatibility purposes."
            //(https://devdocs.magento.com/guides/v2.2/extension-dev-guide/object-manager.html)
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->commandAppend = $objectManager->create('Magento\InventoryImportExport\Model\Import\Command\Append');
            $this->commandAppend->execute($inventorySourceAppendItems);
        }

        if (!empty($inventorySourceDeleteItems)) {
            //"This code needs porting or exist for backward compatibility purposes."
            //(https://devdocs.magento.com/guides/v2.2/extension-dev-guide/object-manager.html)
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->commandDelete = $objectManager->create('Magento\InventoryImportExport\Model\Import\Command\Delete');
            $this->commandDelete->execute($inventorySourceDeleteItems);
        }

        $this->eventManager->dispatch('cobby_import_product_stock_import_after', array( 'products' => $productIds ));

        return $result;
    }
}
