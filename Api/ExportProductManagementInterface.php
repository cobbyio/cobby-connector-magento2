<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ExportProductManagementInterface
{
    /**
     *
     * @api
     * @param string $jsonData
     * @return mixed
     */
    public function export($jsonData);
}
