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
	 * temp header
	 *
	 * @var string
	 */
	protected $header;

	/**
	 * temp header
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * temp hide
	 *
	 * @var string
	 */
	protected $display;

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform():string{

		if(empty($this->content)){
			return '';
		}

		call_user_func([$this, $this->tag]);
		$id = $this->randomID();

		if(!$this->title){
			$this->title = $this->header;
		}

		return '<div data-id="'.$id.'"'.
			$this->getTitle($this->title).
			$this->getCssClass([$this->tag.'-header', 'expander']).'>'.$this->header.'</div>'.
			'<div id="'.$id.'"'.$this->getCssClass([$this->tag.'-body']).
			$this->getStyle(['display' => $this->display ? 'none' : 'block']).'>'.$this->content.'</div>';
	}

	/**
	 * Processes [expander]
	 */
	protected function expander(){
		$this->header  = $this->languageInterface->expanderDisplayExpander();
		$this->display = $this->getAttribute('hide');
	}

	/**
	 * Processes [quote]
	 */
	protected function quote(){
		$name = $this->getAttribute('name');
		$url = $this->checkUrl($this->getAttribute('url'));
		$header = $this->languageInterface->expanderDisplayQuote().($name ? ': '.$name : '');

		$this->title   = $header.($url ? ', source: '.$url : '');
		$this->header  = $header.($url ? ' <small>[<a href="'.$url.'">source</a>]<small>' : '');
		$this->display = $this->getAttribute('hide');
	}

	/**
	 * Processes [spoiler]
	 */
	protected function spoiler(){
		$desc = $this->getAttribute('desc');

		$this->header  = $this->languageInterface->expanderDisplaySpoiler().($desc ? ': <span>'.$desc.'</span>' : '');
		$this->display = true;
	}

	/**
	 * Processes [trigger]
	 */
	protected function trigger(){
		$desc = $this->getAttribute('desc');

		$this->header  = $this->languageInterface->expanderDisplayTrigger().($desc ? ': <span>'.$desc.'</span>' : '');
		$this->display = true;
	}

}
