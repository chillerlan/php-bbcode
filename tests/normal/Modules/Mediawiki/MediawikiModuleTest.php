<?php
/**
 *
 * @filesource   MediawikiModuleTest.php
 * @created      02.03.2016
 * @package      chillerlan\bbcodeTest\normal\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeTest\normal\Modules\Mediawiki;

use chillerlan\bbcode\Modules\Mediawiki\MediawikiBaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use PHPUnit\Framework\TestCase;

class MediawikiModuleTest extends TestCase{

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
