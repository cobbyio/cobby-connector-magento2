<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;


class UpgradeData implements UpgradeDataInterface {

    public function upgrade( ModuleDataSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        if ( version_compare( $context->getVersion(), '2.0.1', '<' ) ) {
            $installer->run("
                TRUNCATE TABLE`{$installer->getTable('cobby_connector_product')}`
            ");

            $installer->run("
                INSERT INTO `{$installer->getTable('cobby_connector_product')}`
                (`entity_id`, `hash`)
                    SELECT `entity_id`, 'init'
                        FROM `{$installer->getTable('catalog_product_entity')}`;
            ");
        }
    }
}