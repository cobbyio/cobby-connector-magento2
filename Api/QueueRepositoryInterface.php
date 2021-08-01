<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Api;

/**
 * @api
 */
interface QueueRepositoryInterface
{
    /**
     *
     * @api
     * @return integer
     */
    public function getMax();

    /**
     *
     * @api
     * @param integer $minQueueId
     * @param integer $pageSize
     * @return \Cobby\Connector\Api\Data\QueueInterface[]
     */
    public function getList($minQueueId, $pageSize);

    /**
     *
     * @api
     * @return int The number of affected rows.
     */
    public function delete();
}
