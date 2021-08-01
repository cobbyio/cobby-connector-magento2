<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Api;

/**
 * @api
 */
interface IndexerRepositoryInterface
{
    /**
     *
     * @api
     * @return mixed
     */
    public function export();

    /**
     * @api
     * @param string $jsonData
     * @return bool
     */
    public function reindex($jsonData);
}
