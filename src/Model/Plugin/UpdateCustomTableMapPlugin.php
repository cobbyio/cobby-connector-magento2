<?php
/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model\Plugin;


use Magento\Setup\Model\FixtureGenerator\EntityGenerator;
use Magento\Setup\Model\FixtureGenerator\EntityGeneratorFactory;

/**
 * Add cobby_connector_queue support table to performance toolkit.
 */
class UpdateCustomTableMapPlugin
{
    /**
     * Processed source items for complex products.
     *
     * @var array
     */
    private $sourceItems = [];

    /**
     * Inject cobby_connector_queue table data to FixtureGenerator\EntityGeneratorFactory arguments.
     *
     * @param EntityGeneratorFactory $subject
     * @param array $data
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeCreate(
        EntityGeneratorFactory $subject,
        array $data
    ): array {
        $data['customTableMap']['cobby_connector_queue'] = [
            'entity_id_field' => EntityGenerator::SKIP_ENTITY_ID_BINDING,
        ];

        return [$data];
    }
}