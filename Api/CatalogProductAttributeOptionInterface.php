<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Api;

/**
 * Interface CatalogProductAttributeOptionInterface
 * @api
 */
interface CatalogProductAttributeOptionInterface
{
    /**
     *
     * Retrieve attribute options
     *
     * @api
     * @param integer $attributeId
     * @return mixed
     */
    public function export($attributeId);

    /**
     * Imports options from cobby
     *
     * @api
     * @param string $jsonData
     * @return mixed
     */
    public function import($jsonData);
}