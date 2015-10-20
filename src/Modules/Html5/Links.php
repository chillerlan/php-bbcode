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

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\Modules\Html5\Html5BaseModule;

/**
 *
 */
class Links extends Html5BaseModule implements ModuleInterface{

	/**
	 * @var array
	 */
	protected $tags = ['url'];

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
	 */
	public function transform(){
		$this->check_tag();

		$url = $this->check_url($this->bbtag() ? : $this->content);

		$host = parse_url($url, PHP_URL_HOST);
		$target = !empty($host) && (isset($_SERVER['SERVER_NAME']) && $host === $_SERVER['SERVER_NAME']) || empty($host) ? 'self' : 'blank';

		return '<a'.($url ? ' href="'.$url.'" target="_'.$target.'"' : '').$this->get_title().$this->get_css_class($target).'>'.$this->content.'</a>';
	}

}
