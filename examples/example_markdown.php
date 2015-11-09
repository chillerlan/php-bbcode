<?php
/**
 * @filesource   example.php
 * @created      19.09.2015
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace Example;

/**
 * Autoloading - composer will most likely do that for you
 */

require_once 'Psr4AutoloaderClass.php';

$loader = new Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('Example', './');
$loader->addNamespace('chillerlan\\bbcode', '../src');

use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use chillerlan\bbcode\Modules\Markdown\MarkdownBaseModule;
use Example\MyAwesomeParserExtension;


header('Content-type: text/plain;charset=utf-8;');

/**
 * Run
 */

$timer = microtime(true);

// create a new Parser instance

$options = new ParserOptions;
$options->sanitize = false;
$options->nesting_limit = 10;
$options->base_module = MarkdownBaseModule::class;
$options->parser_extension = MyAwesomeParserExtension::class;
$options->allow_all = true;

$bbcode = new Parser($options);

var_dump($bbcode->get_tagmap());
#var_dump($bbcode->get_allowed());
#var_dump($bbcode->get_noparse());

$content = $bbcode->parse(file_get_contents('bbcode.txt'));

echo $content.PHP_EOL;

echo PHP_EOL.'bbcode: '.round((microtime(true)-$timer), 6).'s';
