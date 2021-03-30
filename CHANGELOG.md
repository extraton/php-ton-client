CHANGELOG
=========

1.10.0
-----

* Added new interface `AppInterface`
* Added new method `Debot::start`
* Added new method `Debot::fetch`
* Added new method `Debot::execute`
* Added new method `Debot::send`
* Added new method `Debot::remove`
* Added new method `TonClient::resolveAppRequest`
* Added new method `Crypto::registerSigningBox`

1.9.0
-----

* Added new method `Crypto::naclSignDetachedVerify`
* Added new method `Crypto::getSigningBox`
* Added new method `Crypto::signingBoxGetPublicKey`
* Added new method `Crypto::signingBoxSign`
* Added new method `Crypto::removeSigningBox`
* Added new integration and unit tests

1.8.0
-----

* Added new parameter `tuple_list_as_array`
* Fix unit tests

1.7.0
-----

* Added new method `Net::aggregateCollection`
* Added new method `Net::batchQuery`
* Added new integration and unit tests

1.6.0
-----

* Updated TON SDK library to version 1.11.1
* Added new method `Utils::calcStorageFee`
* Added new integration and unit tests

1.5.1
-----

* Removed SmartSleeper
* Added a fixed waiting period for async calls
* Fix integration tests

1.5.0
-----

* Added new method `Abi::encodeInternalMessage`
* Added new integration and unit tests

1.4.0
-----

* Added new method `Boc::cacheGet`
* Added new method `Boc::cacheSet`
* Added new method `Boc::cacheUnpin`
* Added new method `Boc::encodeBoc`
* Added new flag `boc_cache` to methods `Tvm::runTvm` and `Tvm::runExecutor`
* Added new integration and unit tests
* Update docker image

1.3.1
-----

* Fix dev tools
* Fix code-style
* Update docker image to php8.0

1.3.0
-----

* Updated TON SDK library to version 1.10.0
* Added new flag `return_updated_account` to methods `Tvm::runTvm` and `Tvm::runExecutor`
* Fixed integration and unit tests

1.2.0
-----

* Updated TON SDK library to version 1.5.2
* Added PHP 8 support
* Fixed integration tests
* Added new method `Net::query`
* Added new method `Net::findLastShardBlock`
* Added new method `Net::setEndpoints`
* Added new method `Net::fetchEndpoints`
* Added new method `Net::suspend`
* Added new method `Net::resume`
* Added new method `Boc::getCodeFromTvc`

1.1.0
-----

* Added new method `Crypto::chaCha20`
* Added new method `Boc::parseShardstate`
* Added new method `Boc::getBocHash`

1.0.2
-----

* Updated TON SDK library to version 1.1.2
* Added default value (`null`) for some methods
* Added new example: send rubies with comment to surf
* Refactoring for the method `AbiType::fromJson()`

1.0.1
-----

* Fixed parameter name for the method `Net::queryCollection()`

1.0.0
-----

* First public release.