<?php
/**
 *
 * @filesource   VideoTest.php
 * @created      04.03.2016
 * @package      normal\Modules\HTML5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace normal\Modules\HTML5;

use chillerlan\bbcode\Modules\Html5\Html5BaseModule;
use chillerlan\bbcode\Parser;
use chillerlan\bbcode\ParserOptions;
use chillerlan\BBCodeTest\normal\Modules\HTML5\HTML5TestBase;
use Dotenv\Dotenv;

/**
 * Class VideoTest
 */
class VideoTest extends HTML5TestBase{

	protected function setUp(){
		(new Dotenv(self::TESTDIR, self::DOTENV))->load();

		$options = new ParserOptions;
		$options->google_api_key      = getenv('GOOGLE_API');
		$options->vimeo_access_token  = getenv('VIMEO_TOKEN');
		$options->ca_info             = self::TESTDIR.'test-cacert.pem';
		$options->baseModuleInterface = Html5BaseModule::class;
		$options->allow_all           = true;

		$this->parser = new Parser($options);
	}

	public function videoURLDataProvider(){
		return [
			/*
						['https://vimeo.com/136964218', '<iframe src="https://player.vimeo.com/video/136964218" allowfullscreen></iframe>'],
						['https://www.youtube.com/watch?v=6r1-HTiwGiY', '<iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe>'],
						['http://youtu.be/6r1-HTiwGiY', '<iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe>'],
			#*/
			// this test will fail on travis due to missing credentials (coverage)
			['https://vimeo.com/136964218', ''],
			['https://www.youtube.com/watch?v=6r1-HTiwGiY', ''],
			['http://youtu.be/6r1-HTiwGiY', ''],

			['http://www.moddb.com/media/embed/72159', '<div class="bb-video"><iframe src="http://www.moddb.com/media/iframe/72159" allowfullscreen></iframe></div>'],
			['http://dai.ly/x3sjscz', '<div class="bb-video"><iframe src="http://www.dailymotion.com/embed/video/x3sjscz" allowfullscreen></iframe></div>'],
			['http://www.dailymotion.com/video/x3sjscz_the-bmw-m2-is-here-but-does-it-live-up-to-its-legendary-badge-let-s-try-it-out_tech', '<div class="bb-video"><iframe src="http://www.dailymotion.com/embed/video/x3sjscz" allowfullscreen></iframe></div>'],
		];
	}

	/**
	 * @dataProvider videoURLDataProvider
	 */
	public function testVideoModuleURLMatch($url, $expected){
		$this->assertEquals($expected, $this->parser->parse('[video]'.$url.'[/video]'));
	}

	public function videoBBCodeDataProvider(){
		return [
			/*
						// this test will fail on travis due to missing credentials
						['[video]http://youtu.be/6r1-HTiwGiY[/video]', '<div class="bb-video"><iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe></div>'],
						['[youtube]http://youtu.be/6r1-HTiwGiY[/youtube]', '<div class="bb-video"><iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe></div>'],
						['[youtube]6r1-HTiwGiY[/youtube]', '<div class="bb-video"><iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe></div>'],
						['[youtube flash=1]6r1-HTiwGiY[/youtube]', '<div class="bb-video"><object type="application/x-shockwave-flash" data="https://www.youtube.com/v/6r1-HTiwGiY"><param name="allowfullscreen" value="true"><param name="wmode" value="opaque" /><param name="movie" value="https://www.youtube.com/v/6r1-HTiwGiY" /></object></div>'],
						['[youtube wide=1]6r1-HTiwGiY[/youtube]', '<div class="bb-video wide"><iframe src="https://www.youtube.com/embed/6r1-HTiwGiY" allowfullscreen></iframe></div>'],
						['[youtube flash=1 wide=1]6r1-HTiwGiY[/youtube]', '<div class="bb-video wide"><object type="application/x-shockwave-flash" data="https://www.youtube.com/v/6r1-HTiwGiY"><param name="allowfullscreen" value="true"><param name="wmode" value="opaque" /><param name="movie" value="https://www.youtube.com/v/6r1-HTiwGiY" /></object></div>'],
			*/
			// coverage
			['[youtube]6r1-HTiwGiY[/youtube]', ''],

			['[video]http://www.moddb.com/media/embed/72159[/video]', '<div class="bb-video"><iframe src="http://www.moddb.com/media/iframe/72159" allowfullscreen></iframe></div>'],
			['[moddb]http://www.moddb.com/media/embed/72159[/moddb]', '<div class="bb-video"><iframe src="http://www.moddb.com/media/iframe/72159" allowfullscreen></iframe></div>'],
			['[moddb]72159[/moddb]', '<div class="bb-video"><iframe src="http://www.moddb.com/media/iframe/72159" allowfullscreen></iframe></div>'],
			['[moddb flash=1]72159[/moddb]', '<div class="bb-video"><object type="application/x-shockwave-flash" data="http://www.moddb.com/media/embed/72159"><param name="allowfullscreen" value="true"><param name="wmode" value="opaque" /><param name="movie" value="http://www.moddb.com/media/embed/72159" /></object></div>'],
			['[moddb wide=1]72159[/moddb]', '<div class="bb-video wide"><iframe src="http://www.moddb.com/media/iframe/72159" allowfullscreen></iframe></div>'],
			['[moddb flash=1 wide=1]72159[/moddb]', '<div class="bb-video wide"><object type="application/x-shockwave-flash" data="http://www.moddb.com/media/embed/72159"><param name="allowfullscreen" value="true"><param name="wmode" value="opaque" /><param name="movie" value="http://www.moddb.com/media/embed/72159" /></object></div>'],
			['[video]http://some.video.url/whatever[/video]', '<video src="http://some.video.url/whatever" class="bb-video" preload="auto" controls="true"></video>'],
			['[dmotion]x3sjscz[/dmotion]', '<div class="bb-video"><iframe src="http://www.dailymotion.com/embed/video/x3sjscz" allowfullscreen></iframe></div>'],
			['[dmotion flash=1]x3sjscz[/dmotion]', '<div class="bb-video"><object type="application/x-shockwave-flash" data="http://www.dailymotion.com/swf/video/x3sjscz"><param name="allowfullscreen" value="true"><param name="wmode" value="opaque" /><param name="movie" value="http://www.dailymotion.com/swf/video/x3sjscz" /></object></div>'],
		];
	}

	/**
	 * @dataProvider videoBBCodeDataProvider
	 */
	public function testVideoModuleBBCode($bbcode, $expected){
		$this->assertEquals($expected, $this->parser->parse($bbcode));
	}

}
