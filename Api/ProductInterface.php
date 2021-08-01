<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

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