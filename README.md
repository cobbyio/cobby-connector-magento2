[![PHP 7.4](https://img.shields.io/badge/php-7.4-blue.svg)](http://www.php.net)
[![Magento 2](https://img.shields.io/badge/Magento-%32.3,%202.4-blue.svg)](https://github.com/magento/magento2/releases)
# Cobby for Magento 2

[cobby](http://www.cobby.io/) is a PIM system that loves ❤️ Excel. 
Designed to help Magento users manage their online shop catalog faster by connecting all product data in real-time with Excel without any import/export. 
This extension for Magento makes your catalog management much more efficient and faster without any additional knowledge required as all product updates will be made in Excel and directly updated in Magento.and directly updated in Magento.

# Compatibility

- Magento >= 2.1
- Supports both Magento Opensource (Community) and Magento Commerce (Enterprise)

Note: If your store is running under Magento version 1.x, please check cobby for the [Magento 1 extension](https://github.com/cobbyio/cobby-connector-magento).

# Installation

Install using composer by adding to your composer file using commands:

1. composer require cobbyio/cobby-connector-magento2
2. bin/magento module:enable Cobby_Connector
3. bin/magento setup:upgrade

# Configuration

In order to use cobby with Magento, a cobby account has to be created beforehand. Having the account, it’s possible to configure the Magento extension to work properly.

If you don't have a cobby account:

1. Sign up for a free trial at [www.cobby.io](http://www.cobby.io)
2. After you have signed up to cobby, follow the steps of the [configuration wizard](https://www.cobby.io/how-to-install-and-set-up-cobby-in-magento-2/)

# Support

If you have any issues with this extension, open an issue on [GitHub](https://github.com/cobbyio/cobby-connector-magento2/issues).
Alternatively feel free to contact us via email at support@cobby.io or via our website [www.cobby.io](http://www.cobby.io).

# Contribution

Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub.](https://github.com/cobbyio/cobby-connector-magento2/pulls)

# Changelog

See [Changelog](CHANGELOG.md)

# License

See [License](LICENSE.md)