<?php
/**
 * Class TextBaseModule
 *
 * @filesource   TextBaseModule.php
 * @created      17.10.2015
 * @package      chillerlan\bbcode\Modules\Text
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Text;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\BaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;

/**
 *
 */
class TextBaseModule extends BaseModule implements BaseModuleInterface{

	/**
	 * @var array
	 */
	protected $modules = [

	];

	/**
	 * @param $content
	 *
	 * @return string
	 */
	public function sanitize($content){
		// TODO: Implement sanitize() method.
		return 'Implement sanitize() method!';
	}

}
