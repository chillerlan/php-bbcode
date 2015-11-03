# chillerlan/bbcode
[![Packagist](https://img.shields.io/packagist/v/chillerlan/bbcode.svg?style=flat-square)](https://packagist.org/packages/chillerlan/bbcode)
[![License](https://img.shields.io/packagist/l/chillerlan/bbcode.svg?style=flat-square)](LICENSE)

A recursive regexp [BBCode](http://en.wikipedia.org/wiki/BBCode) parser using [preg_replace_callback()](http://php.net/preg_replace_callback),
based on an example by [MrNiceGuy](http://www.developers-guide.net/forums/member/69,mrniceguy) on
[developers-guide.net](http://www.developers-guide.net/c/152-bbcode-parser-mit-noparse-tag-selbst-gemacht.html). 
Handles nested tags aswell as matching brackets and doesn't stumble across invalid tags.

## Requirements
- PHP 5.6+, PHP 7

----

## Documentation
### API
The API docs can be found [over here on github.io](http://codemasher.github.io/docs/) (auto generated from the 
source, using [phpDocumentor](http://www.phpdoc.org/)).

### BBCode docs
A documentation of the included BBCodes is planned and will be available on [the wiki](https://github.com/chillerlan/bbcode/wiki).

### Installation
#### Using [composer](https://getcomposer.org)

*Terminal*
```sh
composer require chillerlan/bbcode
```

*composer.json*
```json
{
	"require": {
		"php": ">=5.6.0",
		"chillerlan/bbcode": "1.0.*"
	}
}
```

#### Manual installation
Download the desired version of the package from [master](https://github.com/chillerlan/bbcode/archive/master.zip) or 
[release](https://github.com/chillerlan/bbcode/releases) and extract the contents to your project folder. 
Point the namespace `chillerlan/bbcode` to the folder `src` of the package.

Profit!

### Usage
First of all, you'll need to import the needed classes of course:
```php
namespace MyProject;

use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
```

#### Parser options
In order to create a `Parser` instance, you'll first want to create an instance of `ParserOptions` and alter it if needed.
However, this step is optional (meta, eh?).
```php
$options = new ParserOptions;
$options->sanitize = true;
$options->nesting_limit = 10;
$options->eol_placeholder = '__MYEOL__';
$options->bbtag_placeholder = '__MYBBTAG__';
$options->base_module = '\\Example\\MyModules\\MyAwesomeBaseModule';
$options->parser_extension = '\\Example\\MyAwesomeParserExtension';
$options->allowed_tags = ['mybbcode', 'somebbcode', 'whatever'];
$options->allow_all = false;
```

#### Parser
Now we're ready to create the `Parser`:
```php
$bbcode = new Parser($options);

// or...

$bbcode = new Parser;
$bbcode->set_options($options);
```

Run the parser and output:
```php
echo $bbcode->parse($some_string_containing_bbcode);
```

In case you need some diagnostics, here you go:
```php
$bbcode->get_tagmap(); // map of tag -> module FQCN
$bbcode->get_allowed(); // an array of all allowed tags
$bbcode->get_noparse(); // an array of all noparse tags
```

That's all!

### Extend the parser
#### Base module
In order to create your own modules, you'll first need an empty base module which contains 
all basic settings and methods for each module. To do so, you'll need to extend `BaseModule` and 
implement `BaseModuleInterface` (both in `\chillerlan\bbcode\Modules`). There's really not much to do,
the only and most important thing is to tell the parser which modules to use. Further, you need to specify
a `sanitize()` method and maybe an EOL token - the rest is up to you and may vary between output types.
```php
namespace Example\MyModules;

use chillerlan\bbcode\Modules\BaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;

class MyAwesomeBaseModule extends BaseModule implements BaseModuleInterface{

	protected $modules = [
		'\\Example\\MyModules\\MyAwesomeModule',
	];

	protected $eol_token = '<br />';
	
	public function sanitize($content){
		return htmlspecialchars($content, ENT_NOQUOTES|ENT_HTML5, 'UTF-8', false);
	}

}
```

#### Encoder module
Now that we have our base module, we're able to create the encoder module, where the actual transform happens.
Each encoder module extends a base module depending on output type (`MyAwesomeBaseModule` here) 
and implements `chillerlan\bbcode\Modules\ModuleInterface`. The property `$tags` and the method `_transform()` are mandatory.
In case your module supports noparse or single tags, you may set the respective properties `$noparse_tags` and `$singletags`.
```php
namespace Example\MyModules;

use chillerlan\bbcode\Modules\ModuleInterface;
use Example\MyModules\MyAwesomeBaseModule;

class MyAwesomeModule extends MyAwesomeBaseModule implements ModuleInterface{

	protected $tags = ['mybbcode', 'somebbcode', 'whatever'];

	public function _transform(){
		if(empty($this->content)){
			return '';
		}

		return '<'.$this->tag.'>'.$this->content.'</'.$this->tag.'>';
	}

}
```

#### Parser extension
The parser features an extension which allows you to alter the bbcode during the parsing process,
namely before and after the main parser unit runs. If you want to create your own parser extension,
just implement `chillerlan\bbcode\ParserExtensionInterface`, set it in the parser options and you're done.
```php
namespace Example;

use chillerlan\bbcode\ParserExtensionInterface;

class MyAwesomeParserExtension implements ParserExtensionInterface{

	public function pre($bbcode){

		$search = [
			"\t", // lets convert all tabs into 4 spaces
		    '{__BASE_URL__}', // assume we use a special token for our base url
		];

		$replace = [
			'    ',
		    'https://your.base/url/'
		];

		return str_replace($search, $replace, $bbcode);
	}

	public function post($bbcode){
		return $bbcode;
	}

}
```

### Notes
The parser may cause some high CPU load, depending on the input. You should never consider to use it somewhere
in your output subsystem - not even with strong caching. Encode on input - you'll want a preview anyway. ;)

### Disclaimer!
I don't take responsibility for molten CPUs, smashed keyboards, broken screens etc.. Use at your own risk!
