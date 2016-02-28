<?php
/**
 * Class Code
 *
 * @filesource   Code.php
 * @created      03.11.2015
 * @package      chillerlan\bbcode\Modules\Markdown
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Markdown;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms several code tags into Markdown
 */
class Code extends MarkdownBaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['code', 'pre', 'css', 'php', 'sql', 'xml', 'html', 'js', 'json'];

	/**
	 * Constructor
	 *
	 * calls self::setBBTemp() in case $bbtemp is set
	 *
	 * @param \chillerlan\bbcode\BBTemp $bbtemp
	 */
	public function __construct(BBTemp $bbtemp = null){
		parent::__construct($bbtemp);

		// set self::$noparse_tags to self::$tags because none of these should be parsed
		$this->noparse_tags = $this->tags;
	}

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

		$this->clearPseudoClosingTags()->clearEOL(PHP_EOL);

		$exceptions = [
			'js'   => 'javascript',
			'pre'  => '',
			'code' => '',
		];

		$content = array_key_exists($this->tag, $exceptions) ? $exceptions[$this->tag] : $this->tag;
		$content .= $this->wrap($this->content, $this->eol_token);

		return $this->wrap($this->wrap($content, '```'), $this->eol_token);
	}

}
