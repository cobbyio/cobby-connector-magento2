<?php

namespace Cobby\Connector\Model;

use Magento\Framework\Api\SortOrder;

class QueueRepository implements \Cobby\Connector\Api\QueueRepositoryInterface
{
    /**
     * Product collection
     *
     * @var \Cobby\Connector\Model\ResourceModel\Queue\Collection
     */
    protected $queueCollection;

    /**
     * @param \Cobby\Connector\Model\ResourceModel\Queue\Collection
     */
    public function __construct(
        \Cobby\Connector\Model\ResourceModel\Queue\Collection $collection
    )
    {
        $this->queueCollection = $collection;
    }

    public function getMax()
    {
        return (int)$this->queueCollection
            ->setOrder('queue_id', SortOrder::SORT_DESC)
            ->setPageSize(1)
            ->getFirstItem()
            ->getData('queue_id');
    }

    public function getList($minQueueId, $pageSize)
    {
        $result = array();
        $items = $this->queueCollection
            ->addMinQueueIdFilter($minQueueId)
            ->setPageSize($pageSize)
            ->setCurPage(1);

        // iterate through station count and create file in each station folder
        foreach ($items as $item) {
            $result[] = $item->getData();
        }

        return $result;
    }

    public function delete()
    {
        $this->queueCollection
            ->delete();
        return true;
    }
}
