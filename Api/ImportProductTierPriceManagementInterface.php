<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ImportProductTierPriceManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @return mixed
     */
    public function import($rows);
}
