<?php
/**
 *
 * @filesource   HTML5ModuleTestCritical.php
 * @created      11.02.2016
 * @package      chillerlan\BBCodeTest\normal
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\BBCodeTest\normal\Modules;

use chillerlan\bbcode\Modules\Html5\Simpletext;
use chillerlan\bbcode\Modules\Html5\Singletags;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use Dotenv\Dotenv;

/**
 * Class HTML5ModuleTestCritical
 */
class HTML5ModuleTest extends \PHPUnit_Framework_TestCase{

	/**
	 * @todo TRAVIS REMINDER!
	 */
	const DOTENV = '.env_example';

	/**
	 * @var \chillerlan\bbcode\Parser
	 */
	protected $parser;

	protected function setUp(){
		(new Dotenv(__DIR__.'/../../', self::DOTENV))->load(); // nasty

		$options = new ParserOptions;
		$options->google_api_key = getenv('GOOGLE_API');
		$options->vimeo_access_token = getenv('VIMEO_TOKEN');
		$options->ca_info = __DIR__.'/../../test-cacert.pem';
		$options->allow_all = true;
		$this->parser = new Parser($options);
	}

	public function testEmptyTags(){
		$singletags = $this->parser->getSingle();
		$exceptions = ['td','th'];

		foreach(array_keys($this->parser->getTagmap()) as $tag){
			if(!in_array($tag, $singletags) && !in_array($tag, $exceptions)){
				$parsed = $this->parser->parse('['.$tag.'][/'.$tag.']');
				$this->assertEquals('', $parsed);
			}
		}
	}

	public function containerDataProvider(){
		return [
			['[div]a div[/div]', '<div class="bb-container left">a div</div>'],
			['[div align=right]an aligned div[/div]', '<div class="bb-container right">an aligned div</div>'],
			['[p]a paragraph[/p]', '<p class="bb-container left">a paragraph</p>'],
			['[p align=right]an aligned paragraph[/p]', '<p class="bb-container right">an aligned paragraph</p>'],
			['[left]left[/left]', '<p class="bb-container left">left</p>'],
			['[left align=right]WAT[/left]', '<p class="bb-container left">WAT</p>'],
			['[right]right[/right]', '<p class="bb-container right">right</p>'],
			['[center]center[/center]', '<p class="bb-container center">center</p>'],
		];
	}

	/**
	 * @dataProvider containerDataProvider
	 */
	public function testContainerModule($bbcode, $expected){
		$parsed = $this->parser->parse($bbcode);
		$this->assertEquals($expected, $parsed);
	}

	public function expanderDataProvider(){
		return [
			['[expander]expander[/expander]', '<div data-id="abcdef12" title="Expander" class="expander-header expander">Expander</div><div id="abcdef12" class="expander-body" style="display:block">expander</div>'],
			['[expander hide=1]expander[/expander]', '<div data-id="abcdef12" title="Expander" class="expander-header expander">Expander</div><div id="abcdef12" class="expander-body" style="display:none">expander</div>'],
			['[quote]quote[/quote]', '<div data-id="abcdef12" title="Quote" class="quote-header expander">Quote</div><div id="abcdef12" class="quote-body" style="display:block">quote</div>'],
			['[quote name=\'some person\' url=http://www.example.com hide=1]quote[/quote]', '<div data-id="abcdef12" title="Quote: some person, source: http://www.example.com" class="quote-header expander">Quote: some person <small>[<a href="http://www.example.com">source</a>]<small></div><div id="abcdef12" class="quote-body" style="display:none">quote</div>'],
			['[spoiler]spoiler[/spoiler]', '<div data-id="abcdef12" title="Spoiler" class="spoiler-header expander">Spoiler</div><div id="abcdef12" class="spoiler-body" style="display:none">spoiler</div>'],
			['[spoiler hide=0]spoiler[/spoiler]', '<div data-id="abcdef12" title="Spoiler" class="spoiler-header expander">Spoiler</div><div id="abcdef12" class="spoiler-body" style="display:none">spoiler</div>'],
			['[trigger]trigger warning[/trigger]', '<div data-id="abcdef12" title="Trigger warning! The following content may be harmful to sensitive audience!" class="trigger-header expander">Trigger warning! The following content may be harmful to sensitive audience!</div><div id="abcdef12" class="trigger-body" style="display:none">trigger warning</div>'],
			['[trigger hide=0]trigger warning[/trigger]', '<div data-id="abcdef12" title="Trigger warning! The following content may be harmful to sensitive audience!" class="trigger-header expander">Trigger warning! The following content may be harmful to sensitive audience!</div><div id="abcdef12" class="trigger-body" style="display:none">trigger warning</div>'],
		];
	}

	/**
	 * @dataProvider expanderDataProvider
	 */
	public function testExpanderModule($bbcode, $expected){
		$parsed = $this->parser->parse($bbcode);
		$parsed = preg_replace('/\"([a-f\d]{8})\"/i', '"abcdef12"', $parsed);
		$this->assertEquals($expected, $parsed);
	}

	public function linkDataProvider(){
		return [
			['[url]https://github.com/chillerlan/bbcode[/url]', '<a href="https://github.com/chillerlan/bbcode" target="_blank" class="blank">https://github.com/chillerlan/bbcode</a>'],
			['[url=https://github.com/chillerlan/bbcode]chillerlan/bbcode[/url]', '<a href="https://github.com/chillerlan/bbcode" target="_blank" class="blank">chillerlan/bbcode</a>'],
			['[url=https://github.com/chillerlan/bbcode title=\'some stuff\' class=some-css-class]chillerlan/bbcode[/url]', '<a href="https://github.com/chillerlan/bbcode" target="_blank" title="some stuff" class="some-css-class blank">chillerlan/bbcode</a>'],
		];
	}

	/**
	 * @dataProvider linkDataProvider
	 */
	public function testLinkModule($bbcode, $expected){
		$parsed = $this->parser->parse($bbcode);
		$this->assertEquals($expected, $parsed);
	}

	public function testListModule(){
		$list_inner_bbcode = '[*]blah[*]blubb[*]foo[*]bar';
		$list_inner_html = '<li>blah</li><li>blubb</li><li>foo</li><li>bar</li>';

		foreach(['0' => 'decimal-leading-zero', '1' => 'decimal', 'a' => 'lower-alpha',
				'A' => 'upper-alpha', 'i' => 'lower-roman', 'I' => 'upper-roman'] as $type => $css){
			// type only
			$parsed = $this->parser->parse('[list type='.$type.']'.$list_inner_bbcode.'[/list]');
			$this->assertEquals('<ol class="bb-list '.$css.'">'.$list_inner_html.'</ol>', $parsed);
			// reversed
			$parsed = $this->parser->parse('[list type='.$type.' reversed=1]'.$list_inner_bbcode.'[/list]');
			$this->assertEquals('<ol reversed="true" class="bb-list '.$css.'">'.$list_inner_html.'</ol>', $parsed);
			// start
			$parsed = $this->parser->parse('[list=42 type='.$type.']'.$list_inner_bbcode.'[/list]');
			$this->assertEquals('<ol start="42" class="bb-list '.$css.'">'.$list_inner_html.'</ol>', $parsed);
			// all
			$parsed = $this->parser->parse('[list=42 type='.$type.' reversed=1 class=foobar title=WAT]'.$list_inner_bbcode.'[/list]');
			$this->assertEquals('<ol start="42" reversed="true" title="WAT" class="foobar bb-list '.$css.'">'.$list_inner_html.'</ol>', $parsed);
		}

		foreach(['c' => 'circle', 'd' => 'disc', 's' => 'square'] as $type => $css){
			$expected = '<ul class="bb-list '.$css.'">'.$list_inner_html.'</ul>';
			// type only
			$parsed = $this->parser->parse('[list type='.$type.']'.$list_inner_bbcode.'[/list]');
			$this->assertEquals($expected, $parsed);

			// should not happen...
			$parsed = $this->parser->parse('[list=42 reversed=1 type='.$type.']'.$list_inner_bbcode.'[/list]');
			$this->assertEquals($expected, $parsed);
		}
	}

	public function testSimpletextModule(){
		foreach(array_keys($this->parser->getTagmap(), Simpletext::class) as $tag){
			$parsed = $this->parser->parse('['.$tag.' class=foobar]WAT[/'.$tag.']');
			$this->assertEquals('<'.$tag.' class="foobar">WAT</'.$tag.'>', $parsed);
		}
	}

	public function testSingletagModule(){
		foreach(array_keys($this->parser->getTagmap(), Singletags::class) as $tag){
			$parsed = $this->parser->parse('['.$tag.']');
			$expected = $tag === 'clear' ? '<br class="bb-clear both" />' : '<'.$tag.' />';
			$this->assertEquals($expected, $parsed);
		}
	}

	public function styledTextDataProvider(){
		return [
			['[color=#424242]color[/color]', '<span class="bb-text color" style="color:#424242">color</span>'],
			['[font=Helvetica]font[/font]', '<span class="bb-text font" style="font-family:Helvetica">font</span>'],
			['[size=42px]size[/size]', '<span class="bb-text size" style="font-size:42px">size</span>'],
			['[tt]typewriter[/tt]', '<span class="bb-text typewriter">typewriter</span>'],
			['[i]italic[/i]', '<span class="bb-text italic">italic</span>'],
			['[b]bold[/b]', '<span class="bb-text bold">bold</span>'],
			['[s]strikethrough[/s]', '<span class="bb-text linethrough">strikethrough</span>'],
			['[u]underline[/u]', '<span class="bb-text underline">underline</span>'],
		];
	}

	/**
	 * @dataProvider styledTextDataProvider
	 */
	public function testStyledTextModule($bbcode, $expected){
		$parsed = $this->parser->parse($bbcode);
		$this->assertEquals($expected, $parsed);
	}

	public function videoURLDataProvider(){
		return [
			['https://vimeo.com/136964218', '<iframe src="https://player.vimeo.com/video/136964218" allowfullscreen></iframe>'],
			['https://www.youtube.com/watch?v=6r1-HTiwGiY', '<iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe>'],
			['http://youtu.be/6r1-HTiwGiY', '<iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe>'],
			['http://www.moddb.com/media/embed/72159', '<iframe src="http://www.moddb.com/media/iframe/72159" allowfullscreen></iframe>'],
			['http://dai.ly/x3sjscz', '<iframe src="http://www.dailymotion.com/embed/video/x3sjscz" allowfullscreen></iframe>'],
			['http://www.dailymotion.com/video/x3sjscz_the-bmw-m2-is-here-but-does-it-live-up-to-its-legendary-badge-let-s-try-it-out_tech', '<iframe src="http://www.dailymotion.com/embed/video/x3sjscz" allowfullscreen></iframe>'],
		];
	}

	/**
	 * @dataProvider videoURLDataProvider
	 */
	public function testVideoModuleURLMatch($url, $expected){
		// this test will fail on travis due to missing credentials
		if(self::DOTENV === '.env'){
			$parsed = $this->parser->parse('[video]'.$url.'[/video]');
			$this->assertEquals('<div class="bb-video">'.$expected.'</div>', $parsed);
		}
	}

	public function videoBBCodeDataProvider(){
		return [
			['[video]http://youtu.be/6r1-HTiwGiY[/video]', '<div class="bb-video"><iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe></div>'],
			['[youtube]http://youtu.be/6r1-HTiwGiY[/youtube]', '<div class="bb-video"><iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe></div>'],
			['[youtube]6r1-HTiwGiY[/youtube]', '<div class="bb-video"><iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe></div>'],
#			['[youtube flash=1]6r1-HTiwGiY[/youtube]', '<div class="bb-video"><object type="application/x-shockwave-flash" data="https://www.youtube.com/v/6r1-HTiwGiY"><param name="allowfullscreen" value="true"><param name="wmode" value="opaque" /><param name="movie" value="https://www.youtube.com/v/6r1-HTiwGiY" /></object></div>'],
			['[youtube wide=1]6r1-HTiwGiY[/youtube]', '<div class="bb-video wide"><iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe></div>'],
			['[youtube flash=1 wide=1]6r1-HTiwGiY[/youtube]', '<div class="bb-video wide"><object type="application/x-shockwave-flash" data="https://www.youtube.com/embed/6r1-HTiwGiY"><param name="allowfullscreen" value="true"><param name="wmode" value="opaque" /><param name="movie" value="https://www.youtube.com/embed/6r1-HTiwGiY" /></object></div>'],
			['[video]http://some.video.url/whatever[/video]', '<video src="http://some.video.url/whatever" class="bb-video" preload="auto" controls="true"></video>'],
		];
	}

	/**
	 * @dataProvider videoBBCodeDataProvider
	 */
	public function testVideoModuleBBCode($bbcode, $expected){
		// this test will fail on travis due to missing credentials
		if(self::DOTENV === '.env'){
			$parsed = $this->parser->parse($bbcode);
			$this->assertEquals($expected, $parsed);
		}
	}

	public function tableDataProvider(){
		return [
			['[table width=300px class=mybbtyble][tr][td]foobar[/td][/tr][/table]', '<table class="mybbtyble bb-table" style="width:300px"><tr><td>foobar</td></tr></table>'],
#			['[table][/table]', ''],
		];
	}

	/**
	 * @dataProvider tableDataProvider
	 */
	public function testTableModule($bbcode, $expected){
		$parsed = $this->parser->parse($bbcode);
		$this->assertEquals($expected, $parsed);
	}


}
