<?php
/**
 * Class Lists
 *
 * @filesource   Lists.php
 * @created      12.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms list tags into HTML5
 */
class Lists extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['list'];

	/**
	 * Map of attribute value -> css property
	 *
	 * @var array
	 */
	protected $types = [
		'0' => 'decimal-leading-zero',
		'1' => 'decimal',
		'a' => 'lower-alpha',
		'A' => 'upper-alpha',
		'i' => 'lower-roman',
		'I' => 'upper-roman',
		'c' => 'circle',
		's' => 'square',
		'd' => 'disc',
	];

	/**
	 * Unordered lists
	 *
	 * @var array
	 */
	protected $ul = ['c', 'd', 's'];

	/**
	 * Ordered lists
	 *
	 * @var array
	 */
	protected $ol = ['0', '1', 'a', 'A', 'i', 'I'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform(){
		if(empty($this->content)){
			return '';
		}

		$start = $this->bbtag();
		$start = is_numeric($start) && $this->attributeIn('type', $this->ol) ? ' start="'.ceil($start).'"' : '';

		$list_tag = count($this->attributes) === 0 || $this->attributeIn('type', $this->ul) ? 'ul' : 'ol';
		$reversed = $this->getAttribute('reversed') && $this->attributeIn('type', $this->ol) ? ' reversed="true"' : '';

		return '<'.$list_tag.$start.$reversed.$this->get_title().$this->get_css_class(['bb-list', $this->attributeKeyIn('type', $this->types, 'disc')]).'>'
		.'<li>'.implode(array_slice(explode('[*]', $this->content), true), '</li><li>').'</li>' // nasty
		.'</'.$list_tag.'>';
	}

}
