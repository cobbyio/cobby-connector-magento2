<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ImportProductBundleManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @return mixed
     */
    public function import($rows);
}