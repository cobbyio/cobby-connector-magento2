<?php

namespace Cobby\Connector\Api;


interface InventorySourceRepositoryInterface
{

    /**
     * @api
     * @return mixed
     */
    public function export();
}