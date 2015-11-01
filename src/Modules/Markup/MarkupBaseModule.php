<?php
/**
 * Class MarkupBaseModule
 *
 * @filesource   MarkupBaseModule.php
 * @created      18.10.2015
 * @package      chillerlan\bbcode\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Markup;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\BaseModule;
use chillerlan\bbcode\Modules\BaseModuleInterface;

/**
 *
 */
class MarkupBaseModule extends BaseModule implements BaseModuleInterface{

	/**
	 * @var array
	 */
	protected $modules = [];

	/**
	 * @var array
	 */
	protected $_style = [];

	/**
	 * @var string
	 * @todo
	 */
#	protected $_css_class_prefix = 'bbcode-';

	/**
	 * @var array
	 * @todo: allowed fonts -> options
	 */
	protected $_allowed_fonts = [
		'Arial',
		'Chicago',
		'Courier New',
		'Geneva',
		'Georgia',
		'Helvetica',
		'Impact',
		'Lucida Sans',
		'Tahoma',
		'Times New Roman',
		'Trebuchet MS',
		'Verdana',
		'Courier, monospace',
	]; // ban comic sans: 'Comic Sans MS',

	/**
	 * @var array
	 */
	protected $_text_align = [
		'left',
		'center',
		'right',
		'justify',
		'start',
		'end',
		'inherit',
	];

	/**
	 * @var array
	 */
	protected $_vertical_align = [
		'baseline',
		'sub',
		'super',
		'text-top',
		'text-bottom',
		'middle',
		'top',
		'bottom',
		'inherit',
	];

	/**
	 * @var
	 * @todo? allowed colors -> options
	 */
#	protected $css_colors = 'aliceblue,antiquewhite,aqua,aquamarine,...';

	/**
	 * @param $content
	 *
	 * @return string
	 */
	public function sanitize($content){
		return htmlspecialchars($content, ENT_NOQUOTES, 'UTF-8', false);
	}

	/**
	 * @return string
	 *
	 * @see https://xkcd.com/221/
	 */
	protected function random_id(){
		return hash('crc32b', mt_rand().microtime(true));
	}

	/**
	 *
	 * @param $url
	 *
	 * @return string
	 */
	protected function check_url($url){
		if(filter_var($url, FILTER_VALIDATE_URL) === false){
			return false;
		}
		// todo: check against whitelist

		return $url;
	}

	/**
	 * @param string $additional_classes
	 *
	 * @return string
	 */
	protected function get_css_class($additional_classes = ''){
		$classes = $this->get_attribute('class', '').' '.$additional_classes;
		$classes = trim(preg_replace('/[^a-z\d\- ]/i', '', $classes));

		return !empty($classes) ? ' class="'.$classes.'"' : '';
	}

	/**
	 * @param $title
	 *
	 * @return string
	 */
	protected function get_title($title = ''){
		$title = $this->get_attribute('title', $title);

		// todo: filter

		return !empty($title) ? ' title="'.$title.'"' : '';
	}

	/**
	 * @return string
	 */
	protected function get_style(){
		$style = [];

		foreach($this->_style as $property => $value){
			// todo: if(in_array($property, $allowed))?
			// handle exclusions of common user definable properties
			switch(true){
				// color
				case in_array($property, ['background-color', 'color'])
					&& !preg_match('/^#([a-f\d]{3}){1,2}$/i', $value):
					// sizes
				case in_array($property, ['font-size', 'line-height', 'width', 'height'])
					&& !preg_match('/^[\d\.]+(px|pt|em|%)$/', $value):
					// yep, it's a merciless fall-through
					$value = false;
					break;
			}

			if($value){
				$style[] = $property.':'.$value;
			}
		}

		return !empty($style) ? ' style="'.implode(';', $style).'"' : '';
	}

}
