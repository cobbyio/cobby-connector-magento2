<?php

namespace Cobby\Connector\Api;

use Magento\Tests\NamingConvention\true\mixed;

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