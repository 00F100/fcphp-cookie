# FcPhp Cookie

Package to manipulate Cookie

[![Build Status](https://travis-ci.org/00F100/fcphp-cookie.svg?branch=master)](https://travis-ci.org/00F100/fcphp-cookie) [![codecov](https://codecov.io/gh/00F100/fcphp-cookie/branch/master/graph/badge.svg)](https://codecov.io/gh/00F100/fcphp-cookie) [![Total Downloads](https://poser.pugx.org/00F100/fcphp-cookie/downloads)](https://packagist.org/packages/00F100/fcphp-cookie)

## How to install

Composer:
```sh
$ composer require 00f100/fcphp-cookie
```

or add in composer.json
```json
{
    "require": {
        "00f100/fcphp-cookie": "*"
    }
}
```

## How to use

```php

use FcPhp\Cookie\Facades\CookieFacade;

/**
 * Facade to return instance of Cookie
 * 
 * @param string $key Key into $_COOKIE of Cookie
 * @param array $cookies Variable $_SESSION
 * @param string $nonce Nonce to Crypto
 * @param string $pathKeys Path to save keys of Crypto
 * @param string $forceNewInstance Force create new instance
 * @return FcPhp\Cookie\Interfaces\ICookie
 */
CookieFacade::getInstance(string $key, array $cookies, string $nonce = null, string $pathKeys = null, bool $forceNewInstance = false) :ICookie

// With No Crypto into content
$cookie = CookieFacade::getInstance('key-cookie', $_COOKIE);

// With Crypto into content
use FcPhp\Crypto\Crypto;
$nonce = Crypto::getNonce();
$cookie = CookieFacade::getInstance('key-cookie', $_COOKIE, $nonce);

// Create new configuration
$cookie->set('item.config', 'value');

// Print: value
echo $cookie->get('item.config');
```