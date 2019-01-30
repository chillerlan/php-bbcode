<?php
/**
 * Class StyledText
 *
 * @filesource   StyledText.php
 * @created      25.04.2018
 * @package      chillerlan\BBCode\Output\Markdown
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\BBCode\Output\Markdown;

class StyledText extends MarkdownModuleAbstract{

	/**
	 * @var array
	 */
	protected $tags = ['b', 'c', 'del', 'i', 'em', 's', 'strong'];

	/**
	 * @return string
	 */
	protected function transform():string{

		if(empty($this->content)){
			return '';
		}

		$str = [
			'b'      => '**', // bold
			'c'      => '`',  // inline code
			'del'    => '~~', // strikethrough
			'em'     => '_',  // italic
			'i'      => '_',  // italic
			's'      => '~~', // strikethrough
			'strong' => '**', // bold
		][$this->tag];

		return $str.$this->content.$str;
	}

}
