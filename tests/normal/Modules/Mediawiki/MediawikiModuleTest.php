<?php
/**
 *
 * @filesource   MediawikiModuleTest.php
 * @created      02.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\Mediawiki;

use chillerlan\bbcode\Modules\Mediawiki\MediawikiBaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;

class MediawikiModuleTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;

	protected function setUp(){
		$options = new ParserOptions;
		$options->baseModuleInterface = MediawikiBaseModule::class ;
		$options->allow_all = true;
		$this->parser = new Parser($options);
	}

	public function testSanitizeCoverage(){
		$this->assertEquals('&', $this->parser->parse('[b]&[/b]'));
	}


}
