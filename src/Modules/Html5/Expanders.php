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

use chillerlan\bbcode\Modules\ModuleInterface;

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
	protected $tags = ['expander', 'quote', 'spoiler', 'trigger'];

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
	public function __transform(){
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
		// todo: $timestamp = $this->getAttribute('timestamp');
		$name = $this->getAttribute('name');
		$header = $name ? ': '.$name : '';

		$url = $this->checkUrl($this->getAttribute('url'));
		$header .= $url ? ' <small>[<a href="'.$url.'">source</a>]<small>' : '';

		$this->_class = 'quote';
		$this->_header = 'Quote'.$header;
		$this->_hide = $this->getAttribute('hide') ? 'none' : 'block';
	}

	/**
	 * Processes [spoiler]
	 */
	private function spoiler(){
		$desc = $this->getAttribute('desc');

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
		$this->_hide = $this->getAttribute('hide') ? 'none' : 'block';
	}

	/**
	 * Processes [trigger]
	 *
	 * @todo translation
	 */
	private function trigger(){
		$this->_class = 'trigger';
		$this->_header = 'Trigger warning! The following content may be harmful to sensitive audience!';
		$this->_hide = 'none';
	}


}
