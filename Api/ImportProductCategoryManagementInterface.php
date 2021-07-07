<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ImportProductCategoryManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @return mixed
     */
    public function import($rows);
}
