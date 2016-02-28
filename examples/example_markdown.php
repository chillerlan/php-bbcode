<?php
/**
 * @filesource   example.php
 * @created      19.09.2015
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeExamples;

require_once '../vendor/autoload.php';

use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use chillerlan\bbcode\Modules\Markdown\MarkdownBaseModule;


header('Content-type: text/plain;charset=utf-8;');

/**
 * Run
 */

$timer = microtime(true);

// create a new Parser instance

$options                           = new ParserOptions;
$options->sanitize                 = false;
$options->nesting_limit            = 10;
$options->baseModuleInterface      = MarkdownBaseModule::class;
$options->parserExtensionInterface = MyAwesomeParserExtension::class;
$options->allow_all                = true;

$bbcode = new Parser($options);

var_dump($bbcode->getTagmap());
#var_dump($bbcode->getAllowed());
#var_dump($bbcode->getNoparse());
#var_dump($bbcode->getSingle());

$content = $bbcode->parse(file_get_contents('bbcode.txt'));

echo $content.PHP_EOL;

echo PHP_EOL.'bbcode: '.round((microtime(true)-$timer), 6).'s';
