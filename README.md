# chillerlan/php-bbcode

A recursive regexp [BBCode](http://en.wikipedia.org/wiki/BBCode) parser for PHP 7+ using [preg_replace_callback()](http://php.net/preg_replace_callback),
based on an example by [MrNiceGuy](http://www.developers-guide.net/forums/member/69,mrniceguy) on
[developers-guide.net](http://www.developers-guide.net/c/152-bbcode-parser-mit-noparse-tag-selbst-gemacht.html).
Handles nested tags as well as matching brackets and doesn't stumble across invalid tags.

[![version][packagist-badge]][packagist]
[![license][license-badge]][license]
[![Travis][travis-badge]][travis]
[![Coverage][coverage-badge]][coverage]
[![Scrunitizer][scrutinizer-badge]][scrutinizer]
[![Packagist downloads][downloads-badge]][downloads]
[![PayPal donate][donate-badge]][donate]

[packagist-badge]: https://img.shields.io/packagist/v/chillerlan/php-bbcode.svg?style=flat-square
[packagist]: https://packagist.org/packages/chillerlan/php-bbcode
[license-badge]: https://img.shields.io/github/license/chillerlan/php-bbcode.svg?style=flat-square
[license]: https://github.com/chillerlan/php-bbcode/blob/master/LICENSE
[travis-badge]: https://img.shields.io/travis/chillerlan/php-bbcode.svg?style=flat-square
[travis]: https://travis-ci.org/chillerlan/php-bbcode
[coverage-badge]: https://img.shields.io/codecov/c/github/chillerlan/php-bbcode.svg?style=flat-square
[coverage]: https://codecov.io/github/chillerlan/php-bbcode
[scrutinizer-badge]: https://img.shields.io/scrutinizer/g/chillerlan/php-bbcode.svg?style=flat-square
[scrutinizer]: https://scrutinizer-ci.com/g/chillerlan/php-bbcode
[downloads-badge]: https://img.shields.io/packagist/dt/chillerlan/php-bbcode.svg?style=flat-square
[downloads]: https://packagist.org/packages/chillerlan/php-bbcode/stats
[donate-badge]: https://img.shields.io/badge/donate-paypal-ff33aa.svg?style=flat-square
[donate]: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WLYUNAT9ZTJZ4

# Requirements
- PHP 7.2+

# Documentation
## Installation
**requires [composer](https://getcomposer.org)**

### *composer.json*
 (note: replace `dev-master` with a [version boundary](https://getcomposer.org/doc/articles/versions.md#summary))
```json
{
	"require": {
		"php": ">=7.2.0",
		"chillerlan/php-bbcode": "dev-master"
	}
}
```

### Manual installation
Download the desired version of the package from [master](https://github.com/chillerlan/php-bbcode/archive/master.zip) or
[release](https://github.com/chillerlan/php-bbcode/releases) and extract the contents to your project folder. After that:
- run `composer install` to install the required dependencies and generate `/vendor/autoload.php`.
- if you use a custom autoloader, point the namespace `chillerlan\BBCode` to the folder `src` of the package

Profit!

## Usage

- @todo

For an [implementation example](https://github.com/codemasher/gw1-database/blob/master/public/gwbbcode.php) see the over here: [gw1-database/GWBBCode](https://github.com/codemasher/gw1-database/tree/master/src/GWBBCode).

### Language
 - @todo

## Notes
The parser may cause some high CPU load, depending on the input. You should never consider to use it somewhere
in your output subsystem - encode on input - you'll want a preview anyway. ;)

You may also run into several bugs. In fact, the BBCoder is essentially a tool to squeeze out any PCRE related bug in PHP known to man (and perhaps unknown). Have fun! ;)
[It is highly recommended to use these php.ini settings](https://github.com/chillerlan/php-bbcode/blob/master/travis-php.ini), especially to disable the PCRE JIT in PHP7 which is a troublemaker.
In case you happen to run into a PCRE related bug, i ask you to open an issue over here along with the bbcode which caused the error and further information.

## Disclaimer!
I don't take responsibility for molten CPUs, smashed keyboards, broken HTML etc.. Use at your own risk!
