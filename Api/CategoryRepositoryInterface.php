<?php
namespace Cobby\Connector\Api;

/**
 * @api
 */
interface CategoryRepositoryInterface
{
    /**
     *
     * @api
     * @param integer $storeId
     * @return mixed
     */
    public function getList($storeId);
}
