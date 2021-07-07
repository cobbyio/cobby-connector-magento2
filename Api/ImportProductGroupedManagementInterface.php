<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ImportProductGroupedManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @return mixed
     */
    public function import($rows);
}
