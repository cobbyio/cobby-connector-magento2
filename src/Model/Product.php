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
     * @param \Magento\Framework\Math\Random $mathRandom
     */
    public function __construct(
        \Magento\Framework\Math\Random $mathRandom
    ){
        $this->_init('Cobby\Connector\Model\ResourceModel\Product');
        $this->mathRandom = $mathRandom;
    }

    public function resetHash($prefix)
    {
        $hash = $prefix.' '.$this->mathRandom->getRandomString(30);

        $this->_getResource()->resetHash($hash);
        return $this;
    }

    public function updateHash($ids)
    {
        $hash = $this->mathRandom->getRandomString(30);
        if (!is_array($ids)) {
            $ids = array($ids);
        }

        foreach(array_chunk($ids, 1024) as $chunk )  {
            $this->_getResource()->updateHash($chunk, $hash);
        }

        return $this;
    }
}