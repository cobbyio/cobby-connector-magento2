<?php
namespace Cobby\Connector\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Cobby\Connector\Helper\Settings;


class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('cobby_connector_product');


        if (version_compare($context->getVersion(), '2.0.1', '<')) {

            if (!$setup->getConnection()->isTableExists($tableName) == true) {
                $table = $setup->getConnection()
                    ->newTable($tableName)
                    ->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Entity ID'
                    )
                    ->addColumn('hash', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 100, [], 'Hash' )
                    ->addColumn('created_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null, array(
                    ), 'Creation Time')
                    ->addColumn('updated_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null, array(
                    ), 'Update Time')
                    ->setComment('Cobby Product Table');

                $setup->getConnection()->createTable($table);
            }
        }

        if (version_compare($context->getVersion(), '2.0.2', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('cobby_connector_queue'),
                'transaction_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'transaction id'
                ]
            );
        }

        $setup->endSetup();

    }
}