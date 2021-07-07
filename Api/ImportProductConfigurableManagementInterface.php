<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ImportProductConfigurableManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @return mixed
     */
    public function import($rows);
}
