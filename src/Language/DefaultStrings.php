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
 * @method string parserExceptionCallback($override_language = null)
 * @method string parserExceptionMatchall($override_language = null)
 *
 * @method string codeDisplayCSS($override_language = null)
 * @method string codeDisplayPHP($override_language = null)
 * @method string codeDisplaySQL($override_language = null)
 * @method string codeDisplayXML($override_language = null)
 * @method string codeDisplayHTML($override_language = null)
 * @method string codeDisplayJS($override_language = null)
 * @method string codeDisplayJSON($override_language = null)
 * @method string codeDisplayPRE($override_language = null)
 * @method string codeDisplayCODE($override_language = null)
 * @method string codeDisplayNSIS($override_language = null)
 *
 * @method string expanderDisplayExpander($override_language = null)
 * @method string expanderDisplayQuote($override_language = null)
 * @method string expanderDisplaySpoiler($override_language = null)
 * @method string expanderDisplayTrigger($override_language = null)
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
