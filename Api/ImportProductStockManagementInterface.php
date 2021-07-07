<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ImportProductStockManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @return mixed
     */
    public function import($rows);
}
