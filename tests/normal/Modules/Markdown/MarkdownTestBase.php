<?php
/**
 *
 * @filesource   MarkdownTestBase.php
 * @created      04.03.2016
 * @package      chillerlan\BBCodeTest\normal\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules\Markdown;

use chillerlan\bbcode\Modules\Markdown\MarkdownBaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use chillerlan\BBCodeTest\normal\Modules\ModuleTestBase;

/**
 * Class MarkdownTestBase
 */
class MarkdownTestBase extends ModuleTestBase{

	protected function setUp(){
		$options = new ParserOptions;
		$options->baseModuleInterface = MarkdownBaseModule::class ;
		$options->allow_all = true;
		$this->parser = new Parser($options);
	}

}
