<?php
/**
 * Class Expanders
 *
 * @filesource   Expanders.php
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
 * Transforms several expander tags into HTML5
 */
class Expanders extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['expander', 'spoiler', 'quote'];

	/**
	 * temp css class
	 *
	 * @var string
	 */
	private $_class;

	/**
	 * temp header
	 *
	 * @var string
	 */
	private $_header;

	/**
	 * temp hide
	 *
	 * @var string
	 */
	private $_hide;

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 * @todo translations
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function _transform(){
		if(empty($this->content)){
			return '';
		}

		call_user_func([$this, $this->tag]);
		$id = $this->random_id();
		$this->_style = ['display' => $this->_hide];

		return '<div data-id="'.$id.'"'.$this->get_title($this->_header).$this->get_css_class($this->_class.'-header expander').'>'.$this->_header.'</div>'.
		'<div id="'.$id.'"'.$this->get_css_class($this->_class.'-body').$this->get_style().'>'.$this->content.'</div>';
	}

	/**
	 * Processes [quote]
	 */
	private function quote(){
		// todo: $timestamp = $this->get_attribute('timestamp');
		$name = $this->get_attribute('name');
		$header = $name ? ': '.$name : '';

		$url = $this->check_url($this->get_attribute('url'));
		$header .= $url ? ' <small>[<a href="'.$url.'">source</a>]<small>' : '';

		$this->_class = 'quote';
		$this->_header = 'Quote'.$header;
		$this->_hide = $this->get_attribute('hide') ? 'none' : 'block';
	}

	/**
	 * Processes [spoiler]
	 */
	private function spoiler(){
		$desc = $this->get_attribute('desc');

		$this->_class = 'spoiler';
		$this->_header = 'Spoiler'.($desc ? ': <span>'.$desc.'</span>' : '');
		$this->_hide = 'none';
	}

	/**
	 * Processes [expander]
	 */
	private function expander(){
		$this->_class = 'expander';
		$this->_header = 'Expander';
		$this->_hide = $this->get_attribute('hide') ? 'none' : 'block';
	}

}
