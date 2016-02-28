<?php
/**
 * Class MyMySimpletext
 *
 * @filesource   MyAwesomeModule.php
 * @created      02.11.2015
 * @package      chillerlan\bbcodeExamples\MyModules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcodeExamples\MyModules;

use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcodeExamples\MyModules\MyAwesomeBaseModule;

/**
 * Transforms several simple text tags into HTML5 (custom)
 */
class MyAwesomeModule extends MyAwesomeBaseModule implements ModuleInterface{

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['mybbcode', 'somebbcode', 'whatever'];

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

		return '<'.$this->tag.'>'.$this->content.'</'.$this->tag.'>';
	}

}
