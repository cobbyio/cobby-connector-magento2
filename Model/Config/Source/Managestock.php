<?php
namespace Cobby\Connector\Model\Config\Source;

class Managestock implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => \Cobby\Connector\Helper\Settings::MANAGE_STOCK_ENABLED, 'label' => __('enabled')],
            ['value' => \Cobby\Connector\Helper\Settings::MANAGE_STOCK_READONLY, 'label' => __('readonly')],
            ['value' => \Cobby\Connector\Helper\Settings::MANAGE_STOCK_DISABLED, 'label' => __('disabled')]
        ];
    }
}