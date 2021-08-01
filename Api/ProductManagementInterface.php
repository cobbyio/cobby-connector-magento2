<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Api;

/**
 * @api
 */
interface ProductManagementInterface
{
    /**
     *
     * @api
     * @param integer $pageNum
     * @param integer $pageSize
     * @return mixed
     */
    public function getList($pageNum, $pageSize);

    /**
     *
     * @api
     * @param string $jsonData
     * @return mixed
     */
    public function updateSkus($jsonData);

    /**
     * @api
     * @param string $jsonData
     * @return mixed
     */
    public function updateWebsites($jsonData);
}
