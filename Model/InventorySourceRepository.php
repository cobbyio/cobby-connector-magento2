<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;


class InventorySourceRepository implements \Cobby\Connector\Api\InventorySourceRepositoryInterface
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * InventorySourceRepository constructor.
     * @param \Magento\Framework\Module\Manager $moduleManager
     */
    public function __construct(
        \Magento\Framework\Module\Manager $moduleManager
    ){
        $this->moduleManager = $moduleManager;
    }

    public function export()
    {
        $result = array();

        $multiSources = $this->moduleManager->isEnabled('Magento_InventoryCatalog');
        if($multiSources) {
            //"This code needs porting or exist for backward compatibility purposes."
            //(https://devdocs.magento.com/guides/v2.2/extension-dev-guide/object-manager.html)
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $sources = $objectManager->create('\Magento\Inventory\Model\SourceRepository');

            $items = $sources->getList()->getItems();

            foreach ($items as $data) {
                $result[] = array(
                    'source_code' => $data['source_code'],
                    'enabled' => $data['enabled'],
                    'name' => $data['name']
                );
            }
        }

        return $result;
    }
}