<?php
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
