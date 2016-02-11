<?php
/**
 *
 * @filesource   ParserPregJitTest.php
 * @created      09.02.2016
 * @package      chillerlan\BBCodeTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\php7;

use chillerlan\bbcode\Parser;

class ParserPregJitTest extends \PHPUnit_Framework_TestCase{

	public function testPregError(){
		$bbcode = file_get_contents(dirname(__FILE__).'/../bbcode_samples/errors/preg_jit.txt');
		(new Parser)->parse($bbcode);
	}

}
