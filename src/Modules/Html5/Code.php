<?php
/**
 * Class Code
 *
 * @filesource   Code.php
 * @created      12.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\ModuleInterface;

/**
 * Transforms several code tags into HTML5
 */
class Code extends Html5BaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['code', 'pre', 'css', 'php', 'sql', 'xml', 'html', 'js', 'json', 'nsis'];

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

		$this->clearPseudoClosingTags()
		     ->clearEol(PHP_EOL);

		$id = $this->random_id();
		$file = $this->getAttribute('file');
		$desc = $this->getAttribute('desc');

		$this->_style = ['display' => $this->getAttribute('hide') ? 'none' : 'block'];

		/**
		 * Map of code tag -> display name
		 * @todo improve, translate
		 */
		$code_display = [
			'css'  => 'Stylesheet/CSS',
			'php'  => 'PHP',
			'sql'  => 'SQL',
			'xml'  => 'XML',
			'html' => 'HTML',
			'js'   => 'JavaScript',
			'json' => 'JSON',
			'pre'  => 'Code',
			'code' => 'Code',
			'nsis' => 'NullSoft Installer Script',
		][$this->tag];

		return '<div data-id="'.$id.'" '.$this->get_title().$this->get_css_class('expander code-header '.$this->tag).'>'
		.$code_display
		.($file ? ' - contents of file "<span>'.$file.'</span>"' : '')
		.($desc ? ' - <span>'.$desc.'</span>' : '')
		.'</div>'
		.'<pre id="'.$id.'"'.$this->get_css_class('code-body').$this->get_style().'>'
		.'<code'.$this->get_css_class('language-'.$this->tag).'>'.$this->sanitize($this->content).'</code></pre>';
	}

}
