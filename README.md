# chillerlan/bbcode
[![Packagist](https://img.shields.io/packagist/v/chillerlan/bbcode.svg)](https://packagist.org/packages/chillerlan/bbcode)
[![License](https://img.shields.io/packagist/l/chillerlan/bbcode.svg?style=flat-square)](LICENSE)

A recursive regexp [BBCode](http://en.wikipedia.org/wiki/BBCode) parser using [preg_replace_callback()](http://php.net/preg_replace_callback),
based on an example by [MrNiceGuy](http://www.developers-guide.net/forums/member/69,mrniceguy) on
[developers-guide.net](http://www.developers-guide.net/c/152-bbcode-parser-mit-noparse-tag-selbst-gemacht.html). 
Handles nested tags aswell as matching brackets and doesn't stumble across invalid tags.

## Requirements
- PHP 5.6+ (not yet PHP 7)

## Installation

### Using [composer](https://getcomposer.org)

#### Terminal
```sh
composer require chillerlan/bbcode:dev-master
```

#### composer.json
```json
{
	"require": {
		"php": ">=5.6.0",
		"chillerlan/bbcode": "dev-master"
	}
}

```

### Manual installation
Download [the latest version of the package](https://github.com/chillerlan/bbcode/archive/master.zip) 
and extract the contents to your project folder. Point the namespace `chillerlan/bbcode` to the folder `src` of the package. 
Profit!

## Usage

### Parser options
In order to create a Parser instance, you'll first want to create an instance of ParserOptions and alter it if needed.
However, this step is optional (meta, eh?).
```php
$options = new ParserOptions;
$options->sanitize = true;
$options->nesting_limit = 10;
$options->eol_placeholder = '__MYEOL__';
$options->bbtag_placeholder = '__MYBBTAG__';
$options->base_module = '\\Example\\MyHtmlBaseModule';
$options->parser_extension = '\\Example\\ExampleParserExtension';
```

### Parser
Now we're ready to create and run the Parser:
```php
$bbcode = new Parser($options);

// or...

$bbcode = new Parser;
$bbcode->set_options($options);

// parse & output
echo $bbcode->parse($some_string_containing_bbcode);
```

In case you need some diagnostics, here you go:
```php
$bbcode->get_tagmap();
$bbcode->get_allowed();
$bbcode->get_noparse_tags();
```

That's all!

## Disclaimer!
I don't take responsibility for molten CPUs, smashed keyboards, broken screens etc.. Use at your own risk!

## License
This work is available under an [MIT style license](LICENSE).
