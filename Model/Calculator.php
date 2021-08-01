<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Model;

use Cobby\Connector\Api\CalculatorInterface;

/**
 * Defines the implementaiton class of the calculator service contract.
 */
class Calculator implements CalculatorInterface
{
    /**
     * Return the sum of the two numbers.
     *
     * @api
     * @param int $num1 Left hand operand.
     * @param int $num2 Right hand operand.
     * @return int The sum of the two values.
     */
    public function add($num1, $num2) {
        return $num1 + $num2+4;
    }
}