<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ImportProductImageManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @return mixed
     */
    public function import($rows);
}
