<?php
/**
 *
 * @filesource   ParserPregJitTest.php
 * @created      09.02.2016
 * @package      chillerlan\bbcodeTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeTest\php7;

use chillerlan\bbcode\Parser;
use PHPUnit\Framework\TestCase;

class ParserPregJitTest extends TestCase{

	// this test will most likely throw an error in case pcre.jit=1
	public function testPregError(){
		$bbcode = file_get_contents(dirname(__FILE__).'/../bbcode_samples/errors/preg_jit.txt');
		(new Parser)->parse($bbcode);
		$this->markTestSkipped('this test will most likely throw an error in case pcre.jit=1');
	}

}
