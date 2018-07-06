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


$cookie = CookieFacade::getInstance();

// Create new configuration
$cookie->set('item.config', 'value');

// Print: value
echo $cookie->get('item.config');
```