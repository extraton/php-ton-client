![Extraton, PHP TON Client](.github/extraton_elephant.png?raw=true)

# Extraton, PHP TON Client
[![php7.4, Ubuntu 20.04](https://github.com/extraton/php-ton-client/workflows/php7.4,%20Ubuntu%2020.04/badge.svg)](https://github.com/extraton/php-ton-client/actions?query=workflow%3A%22php7.4%2C+Ubuntu+20.04%22)
[![php7.4, macOS latest](https://github.com/extraton/php-ton-client/workflows/php7.4,%20macOS%20latest/badge.svg)](https://github.com/extraton/php-ton-client/actions?query=workflow%3A%22php7.4%2C+macOS+latest%22)
[![Total Downloads](https://img.shields.io/packagist/dt/extraton/php-ton-client.svg?style=flat)](https://packagist.org/packages/extraton/php-ton-client)

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
To automatically download the TON SDK library add the following lines to your project composer.json file:
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
The library will be downloaded when the commands ```composer install``` and ```composer update``` are called in your project. To manually download the TON SDK library for your operating system, run the following command:
``` bash
composer run download-ton-sdk-library
```
The TON SDK library will be installed to the directory ```ROOT_OF_YOUR_PROJECT/vendor/extranton/php-ton-client/bin/```.

## Configuring
Minimum configuration needed to start work with TonClient:
```php
$config = [
    'network' => [
        'server_address' => 'net.ton.dev'
    ]
];
```
All configuration options are available [here](https://github.com/tonlabs/TON-SDK/blob/1.0.0/docs/mod_client.md#ClientConfig).
```php
// Create new instance TonClient with default path to TON SDK library
// YOUR_PROJECT_ROOT/vendor/extraton/bin/tonclient.*
$tonClient = new TonClient($config);
```
Also you can specify the path to the library
```php
// Create new instance TonClient with custom path to TON SDK library
$binding = new Binding('PATH_TO_TON_SDK_LIBRARY');
$tonClient = new TonClient($config, $binding);
```


## Examples
Please see [Examples](examples) and [Integration tests](tests/Integration) for more information on detailed usage.

## Change log
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing
``` bash
make test
```

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Notice
If you have any issues, just feel free and open it in this repository, thx!

## Credits
- [Maxim Karanaev](https://github.com/maxvx)
- [qwertys318](https://github.com/qwertys318)

## License
The Apache License Version 2.0. Please see [License File](LICENSE.md) for more information.
