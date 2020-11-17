![Extraton, PHP TON Client](.github/extraton_elephant.png?raw=true)

# Extraton, PHP TON Client
[![php7.4, Ubuntu 20.04](https://github.com/extraton/php-ton-client/workflows/php7.4,%20Ubuntu%2020.04/badge.svg)](https://github.com/extraton/php-ton-client/actions?query=workflow%3A%22php7.4%2C+Ubuntu+20.04%22)
[![php7.4, macOS latest](https://github.com/extraton/php-ton-client/workflows/php7.4,%20macOS%20latest/badge.svg)](https://github.com/extraton/php-ton-client/actions?query=workflow%3A%22php7.4%2C+macOS+latest%22)
[![Total Downloads](https://img.shields.io/packagist/dt/extraton/php-ton-client.svg?style=flat&color=00e600)](https://packagist.org/packages/extraton/php-ton-client) [![Chat on Telegram](https://img.shields.io/badge/chat-on%20Telegram-9cf.svg?logo=telegram&color=0088cc)](https://t.me/extraton)

## Requirements
- php7.4+
- ffi extension
- json extension
- zlib extension

## Installation
To install it via [Composer](https://getcomposer.org/) simply run:
``` bash
composer require extraton/php-ton-client
```
To automatically download the TON SDK library add the following lines to your project `composer.json` file:
``` json
...
  "scripts": {
    "download-ton-sdk-library": "Extraton\\TonClient\\Composer\\Scripts::downloadLibrary",
    "post-update-cmd": [
      "@download-ton-sdk-library"
    ],
    "post-install-cmd": [
      "@download-ton-sdk-library"
    ]
  }
...
```
The library will be downloaded after the commands ```composer install``` and ```composer update``` are called in your project root. To forced download the TON SDK library for your operating system, run the following command:
``` bash
composer run download-ton-sdk-library
```
The TON SDK library will be installed to the directory ```YOUR_PROJECT_ROOT/vendor/extranton/php-ton-client/bin/```.

## Configuring
Simple TonClient instantiation:
```php
$tonClient = TonClient::createDefault();
```
It uses the default configuration:
```php
$config = [
    "network" => [
        'server_address'             => 'net.ton.dev',
        'network_retries_count'      => 5,
        'message_retries_count'      => 5,
        'message_processing_timeout' => 60000,
        'wait_for_timeout'           => 60000,
        'out_of_sync_threshold'      => 30000,
        'access_key'                 => ''
    ],
    'abi'     => [
        'workchain'                              => 0,
        'message_expiration_timeout'             => 60000,
        'message_expiration_timeout_grow_factor' => 1.35
    ],
    'crypto'  => [
        'mnemonic_dictionary'   => 1,
        'mnemonic_word_count'   => 12,
        'hdkey_derivation_path' => "m/44'/396'/0'/0/0",
        'hdkey_compliant'       => true
    ],
];
```
Minimum configuration needed to start working with TonClient:
```php
$config = [
    'network' => [
        'server_address' => 'net.ton.dev'
    ]
];
```
All configuration options are available [here](https://github.com/tonlabs/TON-SDK/blob/1.0.0/docs/mod_client.md#ClientConfig). After instantiate the TonClient will automatically detect operating system and path to the TON SDK library:
```php
// Create new instance with custom configuration and default path to TON SDK library
$tonClient = new TonClient($config);
```
Default path: `YOUR_PROJECT_ROOT/vendor/extraton/bin/tonclient.*`. Also you can specify path by the following lines of code:
```php
// Create new instance TonClient with custom path to TON SDK library
$binding = new Binding('PATH_TO_TON_SDK_LIBRARY');
$tonClient = new TonClient($config, $binding);
```
## Basic usage
```php
// Get TON SDK version
$result = $tonClient->version();

echo "TON SDK version: " , $result->getVersion() . PHP_EOL;
```
```php
// Get instance of Crypto module
$crypto = $tonClient->getCrypto();

// Generate random key pair
$result = $crypto->generateRandomSignKeys();
$keyPair = $result->getKeyPair();

echo 'Public key: ' . $keyPair->getPublic() . PHP_EOL;
echo 'Private key: ' . $keyPair->getSecret() . PHP_EOL;
```

```php
// Get instance of Utils module
$utils = $tonClient->getUtils();

// Convert address to hex format
$result = $utils->convertAddressToHex('ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528');

echo 'Hex: ' . $result->getAddress() . PHP_EOL;
```
## Advanced usage

## Examples
Please see [Examples](examples) and [Integration tests](tests/Integration) for more information on detailed usage.

## ⚠️ Warning
We use experemental [PHP extension FFI](https://www.php.net/manual/en/book.ffi.php). This extension allows the loading of shared libraries, calling of C functions and accessing of C data structures in pure PHP. **This is the only one possible way to async integrate with the TON SDK library.**

Please read **the official warnings** from the developers of php:
>Warning
>This extension is EXPERIMENTAL. The behaviour of this extension including the names of its functions and any other documentation surrounding this extension may change without notice in a future release of PHP. This extension should be used at your own risk.

[FFI Introduction](https://www.php.net/manual/en/intro.ffi.php)

> Although this works, this functionality is not supported on all libffi platforms, is not efficient and leaks resources by the end of request.

[PHP Callbacks](https://www.php.net/manual/en/ffi.examples-callback.php)

We have not detected memory leaks. But sometimes we caught segmentation faults during testing. 🙏 Hopefully the FFI extension will be stabilized in future versions of php.
## Change log
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing
Run the following command to run unit tests:
``` bash
make test-unit
```
... and integration tests:
```bash
make test-integration
```
Some tests use TON SDK methods that listen for asynchronous events. Data from these events is saved to the directory `/tests/Integration/artifacts/`. This way you can analyze them in detail. For example, the test `\Extraton\Tests\Integration\TonClient\ProcessingTest::testProcessMessageWithEvents` uses the call of method `\Extraton\TonClient\Processing::processMessage`. Events received during generator iteration are saved in a file.

## Code Quality
We use [PHPStan](https://github.com/phpstan/phpstan) and [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to control code quality. Run the following commands to analyze the code and fix code style errors:
```bash
make analyze
```
```bash
make codestyle
```
```bash
make codestyle-fix
```
## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Notice
If you have any issues, just feel free and open it in this repository, thx!

## Credits
- [Maxim Karanaev](https://github.com/maxvx)
- [qwertys318](https://github.com/qwertys318)

## License
The Apache License Version 2.0. Please see [License File](LICENSE) for more information.
