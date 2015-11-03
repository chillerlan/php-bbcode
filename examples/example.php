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
use Example\MyAwesomeParserExtension;


header('Content-type: text/html;charset=utf-8;');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="css/normalize.css"/>
	<link rel="stylesheet" href="css/main.css"/>
	<link rel="stylesheet" href="css/bbcode.css"/>
	<link rel="stylesheet" href="css/prism-coy.css"/>
	<title>BBCode parser</title>
</head>
<body>
<?php

/**
 * Run
 */

$timer = microtime(true);

// the encoder base modules - this part might end up in your config
$modules = [
	'MyModule' => '\\Example\\MyModules\\MyAwesomeBaseModule',
	'Html5'    => '\\chillerlan\\bbcode\\Modules\\Html5\\Html5BaseModule',
	'Markdown' => '\\chillerlan\\bbcode\\Modules\\Markdown\\MarkdownBaseModule',
	'Text'     => '\\chillerlan\\bbcode\\Modules\\Text\\TextBaseModule',
];

// create a new Parser instance

$options = new ParserOptions;
$options->sanitize = true;
$options->nesting_limit = 10;
$options->eol_placeholder = '__BBEOL__';
$options->bbtag_placeholder = '__BBTAG__';
$options->base_module = $modules['Html5'];
$options->parser_extension = __NAMESPACE__.'\\MyAwesomeParserExtension';
$options->allowed_tags = [];
$options->allow_all = true;

$bbcode = new Parser($options);

var_dump($bbcode->get_tagmap());
var_dump($bbcode->get_allowed());
var_dump($bbcode->get_noparse());

$content = $bbcode->parse(file_get_contents('bbcode.txt'));

echo $content.PHP_EOL;

echo '<!-- bbcode: '.round((microtime(true)-$timer), 5).'s -->'.PHP_EOL;

?>
<!-- let's assume you use a common js framework in your project -->
<script src="//ajax.googleapis.com/ajax/libs/prototype/1.7.3.0/prototype.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js"></script>
<script src="js/prism.js"></script>
<script>
	(function(){
		document.observe('dom:loaded', function(){
			// open/close expanders
			$$('.expander').invoke('observe', 'click', function(){
				Effect.toggle(this.dataset.id, 'blind');
			});

			// force external links to open in a new window
			$$('.ext-href').invoke('observe', 'click', function(ev){
				Event.stop(ev);
				window.open(this.readAttribute('href'));
			});

			// yada yada yada
		});
	})()
</script>
</body>
</html>
