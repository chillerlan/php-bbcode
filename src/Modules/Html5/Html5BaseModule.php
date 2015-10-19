<?php
/**
 * Class Html5BaseModule
 *
 * @filesource   Html5BaseModule.php
 * @created      13.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\Markup\MarkupBaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;

/**
 *
 */
class Html5BaseModule extends MarkupBaseModule implements BaseModuleInterface{

	/**
	 * @var array
	 */
	protected $modules = [
		__NAMESPACE__.'\\Code',
		__NAMESPACE__.'\\Containers',
		__NAMESPACE__.'\\Expanders',
		__NAMESPACE__.'\\Images',
		__NAMESPACE__.'\\Links',
		__NAMESPACE__.'\\Lists',
		__NAMESPACE__.'\\Noparse',
		__NAMESPACE__.'\\Simpletext',
		__NAMESPACE__.'\\Singletags',
		__NAMESPACE__.'\\Styledtext',
#		__NAMESPACE__.'\\Tables',
#		__NAMESPACE__.'\\Video',
	];

	/**
	 * @var string
	 */
	protected $eol_token = '<br />';

	/**
	 * @param $content
	 *
	 * @return string
	 */
	public function sanitize($content){
		return htmlspecialchars($content, ENT_NOQUOTES | ENT_HTML5, 'UTF-8', false);
	}

}
