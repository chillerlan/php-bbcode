<?php
/**
 * Class Noparse
 *
 * @filesource   Noparse.php
 * @created      17.10.2015
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
class Noparse extends Html5BaseModule implements ModuleInterface{

	/**
	 * @var array
	 */
	protected $tags = ['noparse'];

	/**
	 * @var array
	 */
	protected $noparse_tags = ['noparse'];

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
	 */
	public function _transform(){
		if(empty($this->content)){
			return '';
		}

		// easy stuff: remove the pseudo closing single tags
		$this->clear_pseudo_closing_tags()
		     ->clear_eol(PHP_EOL);

		// todo: <pre>?
		return '<pre class="bb-noparse">'.$this->content.'</pre>';
	}

}
