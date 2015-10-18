<?php
/**
 * Class MarkdownBaseModule
 *
 * @filesource   MarkdownBaseModule.php
 * @created      17.10.2015
 * @package      chillerlan\bbcode\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Markdown;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\BaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;

/**
 *
 */
class MarkdownBaseModule extends BaseModule implements BaseModuleInterface{

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
