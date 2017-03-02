<?php
/**
 *
 * @filesource   DBModuleTest.php
 * @created      02.03.2016
 * @package      chillerlan\bbcodeTest\normal\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeTest\normal\Modules\DB;

use chillerlan\bbcode\Modules\DB\DBBaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use chillerlan\Database\DBOptions;
use chillerlan\Database\Drivers\MySQLi\MySQLiDriver;
use PHPUnit\Framework\TestCase;

class DBModuleTest extends TestCase{

	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;

	protected function setUp(){
		$options = new ParserOptions;
		$options->baseModuleInterface = DBBaseModule::class;

		$options->DBDriver = MySQLiDriver::class;
		$options->DBOptions = new DBOptions();

		$options->allow_all = true;
		$this->parser = new Parser($options);
	}

	public function testSanitizeCoverage(){
		$this->assertEquals('&amp;', $this->parser->parse('[b]&[/b]'));
	}


}
