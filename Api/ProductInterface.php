<?php
namespace Cobby\Connector\Api;

/**
 * Interface ProductInterface
 *
 * @api
 * @package Cobby\Connector\Api
 */
interface ProductInterface
{
    /**
     * @api
     * @param string $hash
     * @return mixed
     */
    public function resetHash($hash);

    /**
     *
     * @api
     * @param integer $ids
     * @return mixed
     */
    public function updateHash($ids);
}