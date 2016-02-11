<?php
/**
 *
 * @filesource   HTML5ModuleTestCritical.php
 * @created      10.02.2016
 * @package      chillerlan\BBCodeTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\critical\Modules;

use chillerlan\bbcode\Modules\Html5\Code;
use chillerlan\bbcode\Modules\Html5\Containers;
use chillerlan\bbcode\Modules\Html5\Html5BaseModule;
use chillerlan\bbcode\Modules\Html5\Singletags;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;

/**
 * Class HTML5ModuleTestCritical
 *
 * may cause the one or other PREG_ERROR or run into a php bug depending on OS and PHP version...
 *
 * @link https://github.com/travis-ci/travis-ci/issues/4593
 * @link https://github.com/travis-ci/travis-ci/issues/5039
 * @link https://github.com/travis-ci/travis-ci/issues/5323
 * @link https://github.com/travis-ci/travis-ci/issues/5332
 */
class HTML5ModuleTestCritical extends \PHPUnit_Framework_TestCase{

	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;

	protected function setUp(){
		$options = new ParserOptions;
		$options->allow_all = true;
		$this->parser = new Parser($options);
	}

	public function bbcodeDataProvider(){
		return [
			// basics
			['', ''], // empty string test (coverage)
			['no bbcode at all', 'no bbcode at all'],
			['[somebbcode]invalid bbcodes will be eaten :P[/somebbcode]', 'invalid bbcodes will be eaten :P'],
			// XSS
			['<script>alert(\'Hello, i am an XSS attempt!\')</script>', '&lt;script&gt;alert(\'Hello, i am an XSS attempt!\')&lt;/script&gt;'],
			['<img src="javascript:alert(\'XSS\');" />', '&lt;img src="javascript:alert(\'XSS\');" /&gt;'],
			['[img]JaVaScRiPt:alert(\'XSS\');[/img]', ''],
			['[img]""><SCRIPT>alert("XSS")</SCRIPT>"[/img]', ''],
			['[img]&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#58;&#97;&#108;&#101;&#114;&#116;&#40;&#39;&#88;&#83;&#83;&#39;&#41;[/img]', ''],
			['[img]¼script¾alert(¢XSS¢)¼/script¾[/img]', ''],
			['[img]vbscript:msgbox("XSS")[/img]', ''],
			['[img]&#0000106&#0000097&#0000118&#0000097&#0000115&#0000099&#0000114&#0000105&#0000112&#0000116&#0000058&#0000097&#0000108&#0000101&#0000114&#0000116&#0000040&#0000039&#0000088&#0000083&#0000083&#0000039&#0000041[/img]', ''],
			['[img alt=Privateinvestocat]https://octodex.github.com/images/privateinvestocat.jpg[/img]', '<img src="https://octodex.github.com/images/privateinvestocat.jpg" alt="Privateinvestocat" class="bb-image" />'],
			// noparse
			['[noparse][u][b]some unparsed bbcode[/b][/u][/noparse]', '<pre class="bbcode noparse">[u][b]some unparsed bbcode[/b][/u]</pre>'],
		];
	}

	/**
	 * @dataProvider bbcodeDataProvider
	 */
	public function testParser($bbcode, $expected){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

	public function nestingDataProvider(){
		return [
			[0,   'bbcode_nesting.txt'],
			[1,   'results/html5_nesting_1.txt'],
			[10,  'results/html5_nesting_10.txt'],
			[100, 'results/html5_nesting_100.txt'],
		];
	}

	/**
	 * @dataProvider nestingDataProvider
	 */
	public function testNesting($limit, $resultfile){
		$options = new ParserOptions;
		$options->allow_all = true;
		$options->nesting_limit = $limit;
		$this->parser->setOptions($options);

		$bbcode = file_get_contents(dirname(__FILE__).'/../../bbcode_samples/bbcode_nesting.txt');
		$expected = file_get_contents(dirname(__FILE__).'/../../bbcode_samples/'.$resultfile);

		$parsed = $this->parser->parse($bbcode);
		// replace the random IDs with something more testable
		$parsed = preg_replace('/\"([a-f\d]{8})\"/i', '"abcdef12"', $parsed);

		$this->assertEquals($expected, $parsed);
	}

	public function testCodeModule(){
		foreach(array_keys($this->parser->getTagmap(), Code::class) as $lang){
			$bbcode = file_get_contents(dirname(__FILE__).'/../../bbcode_samples/bbcode_code_'.$lang.'.txt');
			$expected = file_get_contents(dirname(__FILE__).'/../../bbcode_samples/results/html5_code_'.$lang.'.txt');
			$parsed = $this->parser->parse($bbcode);
			$parsed = preg_replace('/\"([a-f\d]{8})\"/i', '"abcdef12"', $parsed);

			$this->assertEquals($expected, $parsed);
		}
	}

}
