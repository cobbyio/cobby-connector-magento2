<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;

/**
 * Class Product
 * @package Cobby\Connector\Model
 */
class Product extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var
     */
    private $mathRandom;

    /**
     * @var \Cobby\Connector\Model\ResourceModel\Product
     */
    private $productResource;

    /**
     * @param \Magento\Framework\Math\Random $mathRandom
     * @param \Cobby\Connector\Model\ResourceModel\Product $productResource
     */
    public function __construct(
        \Magento\Framework\Math\Random $mathRandom,
        \Cobby\Connector\Model\ResourceModel\Product $productResource
    ){
        $this->_init('Cobby\Connector\Model\ResourceModel\Product');
        $this->mathRandom = $mathRandom;
        $this->productResource = $productResource;
    }

    public function resetHash($prefix)
    {
        $hash = $prefix.' '.$this->mathRandom->getRandomString(30);

        $this->productResource->resetHash($hash);
        return $this;
    }

    public function updateHash($ids)
    {
        $hash = $this->mathRandom->getRandomString(30);
        if (!is_array($ids)) {
            $ids = array($ids);
        }

        foreach(array_chunk($ids, 1024) as $chunk )  {
            $this->productResource->updateHash($chunk, $hash);
        }

        return $this;
    }
}