<?php
namespace Cobby\Connector\Api;

/**
 * Interface ProductAttributeInterface
 * @api
 */
interface ProductAttributeInterface
{
    /**
     *
     * Retrieve related attributes based on given attribute set ID
     *
     * @api
     * @param integer $attributeSetId
     * @return mixed
     */
    public function export($attributeSetId);
}