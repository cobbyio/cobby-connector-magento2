<?php
/**
 * Created by PhpStorm.
 * User: mash2
 * Date: 20.08.18
 * Time: 10:28
 */

namespace Cobby\Connector\Api;

/**
 * Interface ImportProductCustomOptionManagementInterface
 * @api
 * @package Cobby\Connector\Api
 */
interface ImportProductCustomOptionManagementInterface
{
    /**
     * @api
     * @param array $rows
     * @return mixed
     */
    public function import($rows);
}