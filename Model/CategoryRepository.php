<?php

namespace Cobby\Connector\Model;

class CategoryRepository implements \Cobby\Connector\Api\CategoryRepositoryInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    protected $categoryCollection;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection
    )
    {
        $this->categoryCollection = $categoryCollection;
    }

    public function getList($storeId)
    {
        $result = array();

        $categories = $this->categoryCollection
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('is_active')
            ->setStoreId($storeId);

        foreach($categories as $category) {
            if($category->getParentId() == 0 && $category->getId() != 1) {
                continue;
            }

            $result[] = array(
                'category_id' => $category->getId(),
                'parent_id'   => $category->getParentId(),
                'name'        => $category->getName(),
                'is_active'   => $category->getIsActive(),
                'position'    => $category->getPosition(),
                'level'       => $category->getLevel()
            );
        }

        return $result;
    }
}
