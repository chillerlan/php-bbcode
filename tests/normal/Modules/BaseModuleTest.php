<?php
/**
 *
 * @filesource   BaseModuleTest.php
 * @created      13.02.2016
 * @package      normal\Modules
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules;

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\BaseModuleInfo;
use chillerlan\bbcode\Modules\BaseModuleInterface;
use chillerlan\bbcode\Modules\Html5\Html5BaseModule;
use chillerlan\bbcode\Modules\Markdown\MarkdownBaseModule;
use chillerlan\bbcode\Modules\Markup\MarkupBaseModule;
use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\Modules\Text\TextBaseModule;
use ReflectionClass;

class BaseModuleTest extends \PHPUnit_Framework_TestCase{

	const BASE_MODULES = [
		Html5BaseModule::class,
		MarkupBaseModule::class,
		MarkdownBaseModule::class,
		TextBaseModule::class,
	];

	/**
	 * @var \chillerlan\bbcode\BBTemp
	 */
	protected $BBTemp;

	/**
	 * @var \chillerlan\bbcode\Modules\BaseModuleInterface
	 */
	protected $baseModule;

	/**
	 * @var \chillerlan\bbcode\Modules\BaseModuleInfo
	 */
	protected $moduleInfo;

	/**
	 * Holds the current encoder module
	 *
	 * @var \chillerlan\bbcode\Modules\ModuleInterface
	 */
	protected $module;

	protected function setUp(){
		$this->BBTemp = new BBTemp;
	}

	public function testBaseModules(){
		foreach(self::BASE_MODULES as $base_module){
			$baseModuleReflection = new ReflectionClass($base_module);
			$this->assertEquals(BaseModuleInterface::class, $baseModuleReflection->getInterfaceNames()[0]);

			$this->baseModule = $baseModuleReflection->newInstance();
			$this->moduleInfo = $baseModuleReflection->getMethod('getInfo')->invoke($this->baseModule);
			$this->assertInstanceOf(BaseModuleInfo::class, $this->moduleInfo);
			$moduleInfoReflection = new ReflectionClass($this->moduleInfo);

			// mimicking Parser::setOptions() here
			$tagmap = [];
			foreach($moduleInfoReflection->getProperty('modules')->getValue($this->moduleInfo) as $module){
				$moduleReflection = new ReflectionClass($module);
				$this->assertEquals(ModuleInterface::class, $moduleReflection->getInterfaceNames()[1]);

				$this->module = $moduleReflection->newInstanceArgs([$this->BBTemp]);
				$tagmapArray = $moduleReflection->getMethod('getTags')->invoke($this->module);

				foreach($tagmapArray->tags as $tag){
					$tagmap[$tag] = $module;
				}
			}
#			var_dump($tagmap);
		}
	}

}
