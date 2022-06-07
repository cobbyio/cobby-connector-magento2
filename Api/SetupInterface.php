<?php

/*
 * @copyright Copyright (c) 2021 mash2 GmbH & Co. KG. All rights reserved.
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0).
 */

namespace Cobby\Connector\Api;

//use Magento\Tests\NamingConvention\true\mixed;

/**
 *@api
 * Description TODO
 */
interface SetupInterface
{
    /**
     * Description TODO
     * @api
     * @return mixed
     */
    public function export();

    /**
     * Description TODO
     * @api
     * @param string $licenseKey
     * @param bool $clearCache
     * @return bool
     */
    public function import($licenseKey, $clearCache);
}