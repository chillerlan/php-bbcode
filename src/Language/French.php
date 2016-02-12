<?php
/**
 * Class French
 *
 * @filesource   French.php
 * @created      11.02.2016
 * @package      chillerlan\bbcode\Language
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Language;

/**
 *
 */
class French extends LanguageBase implements LanguageInterface{

	public $parserExceptionCallback = '';
	public $parserExceptionMatchall = '';

	public $codeDisplayCSS  = '';
	public $codeDisplayPHP  = '';
	public $codeDisplaySQL  = '';
	public $codeDisplayXML  = '';
	public $codeDisplayHTML = '';
	public $codeDisplayJS   = '';
	public $codeDisplayJSON = '';
	public $codeDisplayPRE  = '';
	public $codeDisplayCODE = '';
	public $codeDisplayNSIS = '';

	public $expanderDisplayExpander = '';
	public $expanderDisplayQuote    = '';
	public $expanderDisplaySpoiler  = '';
	public $expanderDisplayTrigger  = '';

}
