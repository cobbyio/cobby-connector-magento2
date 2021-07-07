<?php
namespace Cobby\Connector\Api\Data;

/**
 * @api
 */
interface QueueInterface
{
    const ID = 'queue_id';

    /**
     * Queue Id
     *
     * @return integer
     */
    public function getQueueId();


}
