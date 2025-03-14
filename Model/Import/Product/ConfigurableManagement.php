<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Import\Product;

class ConfigurableManagement extends AbstractManagement implements \Cobby\Connector\Api\ImportProductConfigurableManagementInterface
{
    /**
     * Super attributes codes
     *
     * @var array
     */
    protected $superAttributes = array();

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    private $prodAttrColFac;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * constructor.
     *
     * @param \Magento\Framework\App\ResourceConnection                                $resourceModel
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory           $productCollectionFactory
     * @param \Magento\Framework\Event\ManagerInterface                                $eventManager
     * @param \Magento\ImportExport\Model\ResourceModel\Helper                         $resourceHelper
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $prodAttrColFac
     * @param \Psr\Log\LoggerInterface                                                 $logger
     * @param \Cobby\Connector\Model\Product                                               $product
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceModel,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $prodAttrColFac,
        \Psr\Log\LoggerInterface $logger,
        \Cobby\Connector\Model\Product $product
    ) {
        parent::__construct($resourceModel, $productCollectionFactory, $eventManager, $resourceHelper, $product);
        $this->prodAttrColFac = $prodAttrColFac;
        $this->logger = $logger;
    }

    private function isAttributeSuper($attrCode)
    {
        return isset($this->superAttributes[$attrCode]);
    }

    private function isValidProductSuperData($productSuperData, $existingProductIds, $associatedProducts)
    {
        $productId = $productSuperData['product_id'];

        if (!in_array($productId, $existingProductIds)) {
            return false;
        }

        foreach ($productSuperData['attributes'] as $attribute) {
            $attributeCode = $attribute['code'];
            if (!$this->isAttributeSuper($attributeCode)) { // check attribute superity
                return false;
            }
        }

        $associatedIds = $productSuperData['associated_ids'];
        if (count(array_intersect_key($associatedIds, $associatedProducts)) !== count($associatedIds)) {
            //NOT required keys exist!
            return false;
        }

        return true;
    }

    /**
     * Returns attributes all values in label-value or value-value pairs form. Labels are lower-cased.
     *
     * @return array
     */
    public function getAttributeOptions($attribute)
    {
        $options = array();

        if ($attribute->usesSource()) {
            $index =  'value';

            // only default (admin) store values used
            $attribute->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID);

            try {
                foreach ($attribute->getSource()->getAllOptions(false) as $option) {
                    $value = is_array($option['value']) ? $option['value'] : array($option);
                    foreach ($value as $innerOption) {
                        if (!empty($innerOption['value'] ?: null)) { // skip ' -- Please Select -- ' option
                            $options[strtolower($innerOption[$index])] = $innerOption['value'];
                        }
                    }
                }
            } catch (\Exception $e) {
                // ignore exceptions connected with source models
                $this->logger->info($e);
            }
        }
        return $options;
    }

    /**
     * Initialize configurable attributes
     *
     * @return array
     */
    private function loadConfigurableAttributes()
    {
        $result = array();

        $attributes = $this->prodAttrColFac->create()
            ->addVisibleFilter()
            ->addFilter('is_global', 1)
            ->addFilter('frontend_input', 'select');

        foreach ($attributes as $attribute) {
            $attributeCode = $attribute->getAttributeCode();
            $attributeId   = $attribute->getId();

            $result[$attributeCode] = array(
                'id'               => $attributeId,
                'code'             => $attributeCode,
                'options'          => $this->getAttributeOptions($attribute)
            );
        }

        return $result;
    }

    public function import($rows)
    {
        $mainTable       = $this->resourceModel->getTableName('catalog_product_super_attribute');

        $productsSuperData = array();
        $usedAssociatedIds = array();

        $productIds = array_keys($rows);

        $this->eventManager->dispatch('cobby_import_product_configurable_import_before', array( 'products' => $productIds ));

        foreach ($rows as $productId => $row) {
            $attributes = $row['attributes'];
            $associatedIds = $row['associated_ids'];
            $attributeSet = $row['attr_set_code'];

            $productsSuperData[] = array(
                'product_id'        => $productId,
                'attr_set_code'     => $attributeSet,
                'attributes'        => $attributes,
                'associated_ids'    => $associatedIds
            );

            $usedAssociatedIds = array_merge($usedAssociatedIds, $associatedIds);
        }

        $usedAssociatedIds = array_unique($usedAssociatedIds);

        $this->superAttributes = $this->loadConfigurableAttributes();
        $associatedProductIds = $this->loadExistingProductIds($usedAssociatedIds);
        $existingProductIds = $this->loadExistingProductIds($productIds);

        $validProductsSuperData = array();
        foreach ($productsSuperData as $productSuperData) {
            if ($this->isValidProductSuperData($productSuperData, $existingProductIds, $associatedProductIds)) {
                $validProductsSuperData[] = $productSuperData;
            }
        }

        $importProductData = array(
            'attributes' => array(),
            'labels'     => array(),
            'pricing'    => array(),
            'super_link' => array(),
            'relation'   => array()
        );

        $nextAttrId      = $this->resourceHelper->getNextAutoincrement($mainTable);

        foreach ($validProductsSuperData as $validProductSuperData) {
            $productId = $validProductSuperData['product_id'];
            $position = 0;
            foreach ($validProductSuperData['attributes'] as $attribute) {
                $productSuperAttrId = $nextAttrId++;
                $attrCode = $attribute['code'];
                $attrId = $attribute['attribute_id'];
                $labels = $attribute['labels'];

                $importProductData['attributes'][$productId][$attrId] = array(
                    'product_super_attribute_id' => $productSuperAttrId, 'position' => $position
                );
                $position++;

                foreach ($labels as $labelData) {
                    $importProductData['labels'][] = array(
                        'product_super_attribute_id' => $productSuperAttrId,
                        'store_id'    => $labelData['store_id'],
                        'use_default' => $labelData['use_default'],
                        'value'       => $labelData['value']
                    );
                }
            }

            foreach ($validProductSuperData['associated_ids'] as $associatedId) {
                $importProductData['super_link'][] = array(
                    'product_id' => $associatedId,
                    'parent_id' => $productId
                );

                $importProductData['relation'][] = array(
                    'parent_id' => $productId,
                    'child_id' => $associatedId
                );
            }
        }

        $this->saveData($importProductData);

        $this->touchProducts($productIds);

        $this->eventManager->dispatch('cobby_import_product_configurable_import_after', array( 'products' => $productIds ));
        return $importProductData;
    }

    protected function saveData($importProductData)
    {
        $mainTable       = $this->resourceModel->getTableName('catalog_product_super_attribute');
        $labelTable      = $this->resourceModel->getTableName('catalog_product_super_attribute_label');
        $linkTable       = $this->resourceModel->getTableName('catalog_product_super_link');
        $relationTable   = $this->resourceModel->getTableName('catalog_product_relation');

        //remove old
        $quoted = $this->connection->quoteInto('IN (?)', array_keys($importProductData['attributes']));
        $this->connection->delete($mainTable, "product_id {$quoted}");
        $this->connection->delete($linkTable, "parent_id {$quoted}");
        $this->connection->delete($relationTable, "parent_id {$quoted}");

        $mainData = array();

        foreach ($importProductData['attributes'] as $productId => $attributesData) {
            foreach ($attributesData as $attrId => $row) {
                $row['product_id']   = $productId;
                $row['attribute_id'] = $attrId;
                $mainData[]          = $row;
            }
        }

        if ($mainData) {
            $this->connection->insertOnDuplicate($mainTable, $mainData);
        }

        if ($importProductData['labels']) {
            $this->connection->insertOnDuplicate($labelTable, $importProductData['labels']);
        }

        if ($importProductData['super_link']) {
            $this->connection->insertOnDuplicate($linkTable, $importProductData['super_link']);
        }

        if ($importProductData['relation']) {
            $this->connection->insertOnDuplicate($relationTable, $importProductData['relation']);
        }

        return true;
    }
}
