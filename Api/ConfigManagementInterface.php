<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ConfigManagementInterface
{
    /**
     *
     * @api
     * @return mixed
     */
    public function getList();

    /**
     *
     * @api
     * @return mixed
     */
    public function getReport();

    /**
     * @api
     * @param string $jsonData
     * @return bool
     */
    public function active($jsonData);
}
