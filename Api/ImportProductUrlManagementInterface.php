<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ImportProductUrlManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @return mixed
     */
    public function import($rows);
}
