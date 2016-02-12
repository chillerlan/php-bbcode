<?php
/**
 * Class DefaultStrings
 *
 * @filesource   DefaultStrings.php
 * @created      12.02.2016
 * @package      chillerlan\bbcode\Language
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Language;

/**
 * property -> LanguageInterface method name
 */
class DefaultStrings extends LanguageBase implements LanguageInterface{

	public $parserExceptionCallback = 'parserExceptionCallback';
	public $parserExceptionMatchall = 'parserExceptionMatchall';

	public $codeDisplayCSS  = 'codeDisplayCSS';
	public $codeDisplayPHP  = 'codeDisplayPHP';
	public $codeDisplaySQL  = 'codeDisplaySQL';
	public $codeDisplayXML  = 'codeDisplayXML';
	public $codeDisplayHTML = 'codeDisplayHTML';
	public $codeDisplayJS   = 'codeDisplayJS';
	public $codeDisplayJSON = 'codeDisplayJSON';
	public $codeDisplayPRE  = 'codeDisplayPRE';
	public $codeDisplayCODE = 'codeDisplayCODE';
	public $codeDisplayNSIS = 'codeDisplayNSIS';

	public $expanderDisplayExpander = 'expanderDisplayExpander';
	public $expanderDisplayQuote    = 'expanderDisplayQuote';
	public $expanderDisplaySpoiler  = 'expanderDisplaySpoiler';
	public $expanderDisplayTrigger  = 'expanderDisplayTrigger';

}
