<?php
/**
 * Class Containers
 *
 * @filesource   Containers.php
 * @created      24.04.2018
 * @package      chillerlan\BBCode\Output\HTML
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\HTML;

class Containers extends HTMLModuleAbstract{

	/**
	 * @var array
	 */
	protected $tags = ['p', 'div', 'left', 'right', 'center'];

	/**
	 * @return string
	 */
	protected function transform():string{

		if(empty($this->content)){
			return '';
		}

		$align_attr = ['left', 'center', 'right', 'justify', 'start', 'end', 'inherit'];
		$tag        = $this->tagIn(['p', 'div'], 'p');
		$align      = $this->tagIn($align_attr, '');

		if(!$align){
			$align = $this->attributeIn('align', $align_attr, 'left');
		}

		return '<'.$tag.' class="bb-container '.$align.'">'.$this->content.'</'.$tag.'>';
	}
}
