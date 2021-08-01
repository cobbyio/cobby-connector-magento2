<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Api;

/**
 * Interface CatalogProductAttributeInterface
 * @api
 */
interface CatalogProductAttributeInterface
{
    /**
     *
     * Retrieve related attributes based on given attribute set ID
     *
     * @api
     * @param int $attributeSetId
     * @param int $attributeId
     * @return mixed
     */
    public function export($attributeSetId = null, $attributeId = null);

//    /**
//     * Retrieve related attribute based on given attributeId
//     *
//     * @api
//     * @param integer $attributeId
//     * @return object
//     */
//    public function info($attributeId);
}