<?php
/**
 * Class Video
 *
 * @filesource   Video.php
 * @created      12.10.2015
 * @package      chillerlan\bbcode\Modules\Html5
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\bbcode\Modules\Html5;

use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\TinyCurl\Traits\RequestTrait;

/**
 * Transforms several video tags into HTML5
 *
 * @todo
 */
class Video extends Html5BaseModule implements ModuleInterface{
	use RequestTrait;

	/**
	 * An array of tags the module is able to process
	 *
	 * @var array
	 * @see \chillerlan\bbcode\Modules\Tagmap::$tags
	 */
	protected $tags = ['video', 'dmotion', 'vimeo', 'youtube', 'moddb'];

	/**
	 * temp flash
	 *
	 * @var string
	 */
	protected $flash;

	/**
	 * @var array
	 */
	protected $cssclass = ['bb-video'];

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform():string{
		$this->setRequestCA($this->parserOptions->ca_info);

		if(empty($this->content)){
			return '';
		}

		$this->flash = $this->getAttribute('flash');

		if($this->getAttribute('wide')){
			$this->cssclass[] = 'wide';
		}

		return $this->getPlayer();
	}

	/**
	 * Gets the video provider
	 *
	 * @return string
	 */
	protected function getPlayer():string{
		$bbtag = $this->bbtag();
		$url   = parse_url($this->content);
		$host  = isset($url['host']) ? str_replace('www.', '', $url['host']) : false;

		switch(true){
			case $this->tag === 'vimeo' || $bbtag === 'vimeo' || $host === 'vimeo.com':
				return $this->vimeo();
			case $this->tag === 'youtube' || $bbtag === 'youtube' || in_array($host, ['youtube.com', 'youtu.be']):
				return $this->youtube($host, $url);
			case $this->tag === 'moddb' || $bbtag === 'moddb' || $host === 'moddb.com':
				return $this->moddb($host, $url);
			case $this->tag === 'dmotion' || $bbtag === 'dmotion' || in_array($host, ['dailymotion.com', 'dai.ly']):
				return $this->dailymotion($host, $url);
			default:
				return $this->html5Player();
		}

	}

	/**
	 * @param string $video_url
	 *
	 * @return string
	 */
	protected function flashPlayer(string $video_url):string{

		return '<div'.$this->getCssClass($this->cssclass).'>'
		       .'<object type="application/x-shockwave-flash" data="'.$video_url.'">'
		       .'<param name="allowfullscreen" value="true">'
		       .'<param name="wmode" value="opaque" />'
		       .'<param name="movie" value="'.$video_url.'" />'
		       .'</object></div>';
	}

	/**
	 * @param string $video_url
	 *
	 * @return string
	 */
	protected function embedPlayer(string $video_url):string{

		return '<div'.$this->getCssClass($this->cssclass).'>'
		       .'<iframe src="'.$video_url.'" allowfullscreen></iframe></div>';
	}

	/**
	 * @return string
	 */
	protected function html5Player():string{

		return '<video src="'.$this->checkUrl($this->content).'"'
		       .$this->getCssClass($this->cssclass).' preload="auto" controls="true"></video>';
	}

	/**
	 * @param string $host
	 * @param array  $url
	 *
	 * @return string
	 */
	protected function dailymotion(string $host, array $url):string{

		if($host === 'dailymotion.com'){
			$id = explode('_', str_replace('/video/', '', $url['path']), 2)[0];
		}
		else if($host === 'dai.ly'){
			$id = $url['path'];
		}
		else{
			$id = $this->content;
		}

		$id = preg_replace('#[^a-z\d]#i', '', $id);

		return $this->flash
			? $this->flashPlayer('http://www.dailymotion.com/swf/video/'.$id)
			: $this->embedPlayer('http://www.dailymotion.com/embed/video/'.$id);
	}

	/**
	 * @param string $host
	 * @param array  $url
	 *
	 * @return string
	 */
	protected function moddb(string $host, array $url):string{
		$id = $this->content;

		if($host === 'moddb.com' && strpos($this->content, 'http://www.moddb.com/media/') === 0){
			$id = $url['path'];
		}

		$id = preg_replace('/[^\d]/', '', $id);

		return $this->flash
			? $this->flashPlayer('http://www.moddb.com/media/embed/'.$id)
			: $this->embedPlayer('http://www.moddb.com/media/iframe/'.$id);
	}

	/**
	 * @return string
	 */
	protected function vimeo():string{
		// since the video id is the only numeric part in a common vimeo share url, we can safely strip anything which is not number
		$id = preg_replace('/[^\d]/', '', $this->content);

		// @todo collect & batch request
		$response = $this->fetch('https://api.vimeo.com/videos/'.$id, ['access_token' => $this->parserOptions->vimeo_access_token])->json;

		// access token needed - no coverage
		// @codeCoverageIgnoreStart
		if(isset($response->link)){
			// @todo add fancyness
			return $this->flash
				? $this->flashPlayer('https://vimeo.com/moogaloop.swf?clip_id='.$id)
				: $this->embedPlayer('https://player.vimeo.com/video/'.$id);
		}
		// @codeCoverageIgnoreEnd

		return '';
	}

	/**
	 * @param string $host
	 * @param array  $url
	 *
	 * @return string
	 */
	protected function youtube(string $host, array $url):string{

		if($host === 'youtube.com'){
			parse_str($url['query'], $q);
			$id = $q['v'];
		}
		else if($host === 'youtu.be'){
			$e = explode('/', $url['path'], 2);
			$id = isset($e[1]) ? $e[1] : false;
		}
		else{
			$id = $this->content;
		}

		if($id){

			// check video (and get data)
			$params = [
				'id' => preg_replace('/[^a-z\d-_]/i', '', $id),
				'part' => 'snippet',
				'key' => $this->parserOptions->google_api_key,
			];

			$response = $this->fetch('https://www.googleapis.com/youtube/v3/videos', $params)->json;

			// api key needed - no coverage
			// @codeCoverageIgnoreStart
			if(isset($response->items, $response->items[0]) && $response->items[0]->id === $id){
				// @todo support playlists
				return $this->flash
					? $this->flashPlayer('https://www.youtube.com/v/'.$id)
					: $this->embedPlayer('https://www.youtube.com/embed/'.$id);
			}
			// @codeCoverageIgnoreEnd
		}

		return '';
	}

}
