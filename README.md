[![version][packagist-badge]][packagist]
[![license][license-badge]][license]
[![Travis][travis-badge]][travis]
[![Coverage][coverage-badge]][coverage]
[![Issues][issue-badge]][issues]
[![SensioLabsInsight][sensio-badge]][sensio]
[![Scrunitizer][scrutinizer-badge]][scrutinizer]
[![GitterChat][gitter-badge]][gitter]

[packagist-badge]: https://img.shields.io/packagist/v/chillerlan/bbcode.svg?style=flat-square
[packagist]: https://packagist.org/packages/chillerlan/bbcode
[license-badge]: https://img.shields.io/packagist/l/chillerlan/bbcode.svg?style=flat-square
[license]: https://github.com/chillerlan/bbcode/blob/master/LICENSE
[travis-badge]: https://img.shields.io/travis/chillerlan/bbcode.svg?style=flat-square
[travis]: https://travis-ci.org/chillerlan/bbcode
[coverage-badge]: https://img.shields.io/codecov/c/github/chillerlan/bbcode.svg?style=flat-square
[coverage]: https://codecov.io/github/chillerlan/bbcode
[issue-badge]: https://img.shields.io/github/issues/chillerlan/bbcode.svg?style=flat-square
[issues]: https://github.com/chillerlan/bbcode/issues
[sensio-badge]: https://img.shields.io/sensiolabs/i/9daeaa2a-abdc-43a6-bb8c-945e52e34751.svg?style=flat-square
[sensio]: https://insight.sensiolabs.com/projects/9daeaa2a-abdc-43a6-bb8c-945e52e34751
[scrutinizer-badge]: https://scrutinizer-ci.com/g/chillerlan/bbcode/badges/quality-score.png?b=master
[scrutinizer]: https://scrutinizer-ci.com/g/chillerlan/bbcode
[gitter-badge]: https://img.shields.io/gitter/room/nwjs/nw.js.svg?style=flat-square
[gitter]: https://gitter.im/chillerlan/bbcode

# chillerlan/bbcode

A recursive regexp [BBCode](http://en.wikipedia.org/wiki/BBCode) parser using [preg_replace_callback()](http://php.net/preg_replace_callback),
based on an example by [MrNiceGuy](http://www.developers-guide.net/forums/member/69,mrniceguy) on
[developers-guide.net](http://www.developers-guide.net/c/152-bbcode-parser-mit-noparse-tag-selbst-gemacht.html). 
Handles nested tags aswell as matching brackets and doesn't stumble across invalid tags.

## Requirements
- PHP 7

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
composer require chillerlan/bbcode:dev-master
```

*composer.json*
```json
{
	"require": {
		"php": ">=7.0.0",
		"chillerlan/bbcode": "dev-master"
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
$options->languageInterface = MyLanguage::class;
$options->baseModuleInterface = MyAwesomeBaseModule::class;
$options->parserExtensionInterface = MyAwesomeParserExtension::class;
$options->sanitize = true;
$options->nesting_limit = 10;
$options->eol_placeholder = '__MYEOL__';
$options->bbtag_placeholder = '__MYBBTAG__';
$options->allowed_tags = ['mybbcode', 'somebbcode', 'whatever'];
$options->allow_all = false;
```

#### Parser
Now we're ready to create the `Parser`:
```php
$bbcode = new Parser($options);

// or...

$bbcode = new Parser;
$bbcode->setOptions($options);
```

Run the parser and output:
```php
echo $bbcode->parse($some_string_containing_bbcode);
```

In case you need some diagnostics, here you go:
```php
$bbcode->getTagmap();  // map of tag -> module FQCN
$bbcode->getAllowed(); // all allowed tags
$bbcode->getNoparse(); // all noparse tags
$bbcode->getSingle();  // all singletags

// get all tags of a module
$module_tags = array_keys($bbcode->getTagmap(), MyAwesomeModule::class);
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
use Example\MyModules\MyAwesomeModule;

class MyAwesomeBaseModule extends BaseModule implements BaseModuleInterface{

	protected $modules = [
		MyAwesomeModule::class,
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
and implements `\chillerlan\bbcode\Modules\ModuleInterface`. The property `$tags` and the method `__transform()` are mandatory.
In case your module supports noparse or single tags, you may set the respective properties `$noparse_tags` and `$singletags`.
```php
namespace Example\MyModules;

use chillerlan\bbcode\Modules\ModuleInterface;
use Example\MyModules\MyAwesomeBaseModule;

class MyAwesomeModule extends MyAwesomeBaseModule implements ModuleInterface{

	protected $tags = ['mybbcode', 'somebbcode', 'whatever'];

	public function __transform(){
		if(empty($this->content)){
			return '';
		}

		return '<'.$this->tag.'>'.$this->content.'</'.$this->tag.'>';
	}

}
```

You can also extend one of the existing modules to alter their behaviour, for example if you want the module to support more bbcodes. 
In this case, you should be aware that the module already extends a base module, which will be used instead of your own.
However, the module information, EOL token and sanitize method of your base module will be used in the parser then 
and should match the extended module's parent.
```php
namespace Example\MyModules;

use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\Modules\Html5\Simpletext;

class MyAwesomeModule extends Simpletext implements ModuleInterface{

	protected $tags = [
		'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'strong', 'sub', 'sup', 'del', 'small', // default tags
		'mybbcode', 'somebbcode', 'whatever', // your own tags
	];

}
```

#### Parser extension
The parser features an extension which allows you to alter the bbcode during the parsing process,
namely before and after the main parser unit runs. If you want to create your own parser extension,
just implement `\chillerlan\bbcode\ParserExtensionInterface`, set it in the parser options and you're done.
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
#### Language

### Notes
The parser may cause some high CPU load, depending on the input. You should never consider to use it somewhere
in your output subsystem - not even with strong caching. Encode on input - you'll want a preview anyway. ;)

You may also run into several bugs. In fact, the BBCoder is essentially a tool to squeeze out any PCRE related bug in PHP known to man (and perhaps unknown). Have fun! ;)
[It is highly recommended to use these php.ini settings](https://github.com/chillerlan/bbcode/blob/master/travis-php.ini), especially to disable the PCRE JIT in PHP7 which is a troublemaker.
In case you happen to run into a PCRE related bug, i ask you to open an issue over here along with the bbcode which caused the error and further information.

### Disclaimer!
I don't take responsibility for molten CPUs, smashed keyboards, broken HTML etc.. Use at your own risk!
