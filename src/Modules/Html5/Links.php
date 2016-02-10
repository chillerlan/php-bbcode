<?php
/**
 * Class Links
 *
 * @filesource   Links.php
 * @created      12.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms link tags into HTML5
 */
class Links extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['url'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function _transform(){
		$url = $this->check_url($this->bbtag() ? : $this->content);

		$host = parse_url($url, PHP_URL_HOST);
		$target = !empty($host) && (isset($_SERVER['SERVER_NAME']) && $host === $_SERVER['SERVER_NAME']) || empty($host) ? 'self' : 'blank';

		return '<a'.($url ? ' href="'.$url.'" target="_'.$target.'"' : '').$this->get_title().$this->get_css_class($target).'>'.$this->content.'</a>';
	}

}
