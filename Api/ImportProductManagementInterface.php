<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ImportProductManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @param string $transactionId
     * @return mixed
     */
    public function import($rows, $transactionId);
}
