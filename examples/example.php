<?php
/**
 * @filesource   example.php
 * @created      19.09.2015
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

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
 * Autoloading
 */

require_once 'Psr4AutoloaderClass.php';

$loader = new \Example\Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('Example', './');
$loader->addNamespace('chillerlan\bbcode', '../src');

use chillerlan\bbcode\Parser;
use Example\ExampleParserExtension;

/**
 * Run
 */

$modules = [
	'Html5'    => '\\chillerlan\\bbcode\\Modules\\Html5\\Html5BaseModule',
	'Markdown' => '\\chillerlan\\bbcode\\Modules\\Markdown\\MarkdownBaseModule',
	'Text'     => '\\chillerlan\\bbcode\\Modules\\Text\\TextBaseModule',
];

$bbcode = new Parser(new $modules['Html5'], 10);
$content = $bbcode
				->set_parser_extension(new ExampleParserExtension)
				->parse(file_get_contents('bbcode.txt'));

#var_dump($bbcode->get_tagmap());
#var_dump($bbcode->get_noparse());

echo $content.PHP_EOL;

?>
<script src="//ajax.googleapis.com/ajax/libs/prototype/1.7.3.0/prototype.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js"></script>
<script src="js/prism.js"></script>
<script>
	(function(){
		document.observe('dom:loaded', function(){
			$$('.expander').invoke('observe', 'click', function(){
				Effect.toggle(this.dataset.id, 'blind');
			});
		});
	})()
</script>
</body>
</html>
