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

namespace chillerlan\bbcodeTest\normal\Modules;

use chillerlan\bbcode\Modules\BaseModuleInfo;
use chillerlan\bbcode\Modules\BaseModuleInterface;
use chillerlan\bbcode\Modules\DB\DBBaseModule;
use chillerlan\bbcode\Modules\Html5\Html5BaseModule;
use chillerlan\bbcode\Modules\Markdown\MarkdownBaseModule;
use chillerlan\bbcode\Modules\Markup\MarkupBaseModule;
use chillerlan\bbcode\Modules\Mediawiki\MediawikiBaseModule;
use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\Modules\Text\TextBaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use PHPUnit\Framework\TestCase;

class BaseModuleTest extends TestCase{

	/**
	 * @var \chillerlan\bbcode\Modules\BaseModuleInterface
	 */
	protected $baseModule;

	/**
	 * Holds the current encoder module
	 *
	 * @var \chillerlan\bbcode\Modules\ModuleInterface
	 */
	protected $module;

	public function baseModuleDataProvider(){
		// @todo
		return [
			[DBBaseModule::class,        ],
			[Html5BaseModule::class,     ],
			[MarkupBaseModule::class,    ],
			[MarkdownBaseModule::class,  ],
			[MediawikiBaseModule::class, ],
			[TextBaseModule::class,      ],
		];

	}

	/**
	 * @dataProvider baseModuleDataProvider
	 */
	public function testBaseModules($base_module){
		$this->baseModule = new $base_module;
		$this->assertInstanceOf(BaseModuleInterface::class, $this->baseModule);
		$this->assertInstanceOf(BaseModuleInfo::class, $this->baseModule->getInfo());
		// wrap coverage
		$this->assertEquals('__test__', $this->baseModule->wrap('test', '__'));
	}

	/**
	 * @dataProvider baseModuleDataProvider
	 * @expectedException \chillerlan\BBCode\BBCodeException
	 * @expectedExceptionMessage tag [] not supported.
	 */
	public function testCheckTagException($base_module){
		$this->baseModule = new $base_module;
		$this->baseModule->checkTag();
	}

	/**
	 * @dataProvider baseModuleDataProvider
	 */
	public function testMimickParserSetoptions($base_module){

		$options = new ParserOptions;
		$options->ca_info             = __DIR__.'/../../test-cacert.pem';
		$options->baseModuleInterface = $base_module;
		$options->allow_all           = true;

		$tags = (new Parser($options))->getAllowed();

		// mimicking Parser::setOptions() here
		$this->baseModule = new $base_module;

		$modules = $this->baseModule->getInfo()->modules;
		if(!empty($modules)){
			foreach($modules as $module){
				$this->module = new $module;
				$this->assertInstanceOf(ModuleInterface::class, $this->module);

				$module_tags = $this->module->getTags()->tags;
				if(!empty($module_tags)){
					foreach($module_tags as $tag){
						$this->assertContains($tag, $tags);
					}
				}
				else{
					$this->markTestSkipped('no tags in module '.get_class($this->module));
				}
			}
		}
		else{
			$this->markTestSkipped('nada '.get_class($this->baseModule));
		}

	}

}
