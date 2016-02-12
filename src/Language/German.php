<?php
/**
 * Class German
 *
 * @filesource   German.php
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
class German extends LanguageBase implements LanguageInterface{

	public $parserExceptionCallback = 'preg_replace_callback() verursachte einen %2$s (%3$s) am tag [%1$s]';
	public $parserExceptionMatchall = 'preg_match_all() verursachte einen %1$s (%2$s)';

	public $codeDisplayCSS  = 'Stylesheet/CSS';
	public $codeDisplayPHP  = 'PHP';
	public $codeDisplaySQL  = 'SQL';
	public $codeDisplayXML  = 'XML';
	public $codeDisplayHTML = 'HTML';
	public $codeDisplayJS   = 'JavaScript';
	public $codeDisplayJSON = 'JSON';
	public $codeDisplayPRE  = 'Code';
	public $codeDisplayCODE = 'Code';
	public $codeDisplayNSIS = 'NullSoft Installer Script';

	public $expanderDisplayExpander = 'expanderDisplayExpander';
	public $expanderDisplayQuote    = 'expanderDisplayQuote';
	public $expanderDisplaySpoiler  = 'expanderDisplaySpoiler';
	public $expanderDisplayTrigger  = 'expanderDisplayTrigger';

}
