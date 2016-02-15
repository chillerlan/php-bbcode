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
use chillerlan\bbcode\Traits\RequestTrait;

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
	 * temp host
	 *
	 * @var string
	 */
	protected $host;

	/**
	 * temp url
	 *
	 * @var array
	 */
	protected $url;

	/**
	 * temp flash
	 *
	 * @var string
	 */
	protected $flash;

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function __transform(){
		$this->setRequestCA($this->parserOptions->ca_info);

		if(empty($this->content)){
			return '';
		}

		$this->url   = parse_url($this->content);
		$this->host  = isset($this->url['host']) ? str_replace('www.', '', $this->url['host']) : false;
		$this->flash = $this->getAttribute('flash');

		$provider    = $this->getProvider();
		$video_url   = call_user_func([$this, $provider]);

		if($provider === 'video'){
			return '<video src="'.$video_url.'"'.$this->getCssClass('bb-video').' preload="auto" controls="true"></video>';
		}
		else{
			$object = '<iframe src="'.$video_url.'" allowfullscreen></iframe>';

			if($this->flash){
				$object = '<object type="application/x-shockwave-flash" data="'.$video_url.'">'
					.'<param name="allowfullscreen" value="true">'
					.'<param name="wmode" value="opaque" />'
					.'<param name="movie" value="'.$video_url.'" />'
					.'</object>';
			}

			return '<div'.$this->getCssClass(['bb-video']).'>'.$object.'</div>';
		}
	}

	/**
	 * Gets the video provider
	 *
	 * @return string
	 */
	protected function getProvider(){
		$bbtag = $this->bbtag();

		switch(true){
			case $this->tag === 'vimeo' || $bbtag === 'vimeo' || $this->host === 'vimeo.com':
				return 'vimeo';
			case $this->tag === 'youtube' || $bbtag === 'youtube' || in_array($this->host, ['youtube.com', 'youtu.be']):
				return 'youtube';
			case $this->tag === 'moddb' || $bbtag === 'moddb' || $this->host === 'moddb.com':
				return 'moddb';
			case $this->tag === 'dmotion' || $bbtag === 'dmotion' || in_array($this->host, ['dailymotion.com', 'dai.ly']):
				return 'dmotion';
			default:
				return 'video';
		}
	}

	/**
	 * Processes Daily Motion videos
	 */
	protected function dmotion(){

		switch($this->host){
			case 'dailymotion.com':
				$id = explode('_', str_replace('/video/', '', $this->url['path']), 2)[0];
				break;
			case 'dai.ly':
				$id = $this->url['path'];
				break;
			default:
				$id = $this->content;
				break;
		}

		return 'http://www.dailymotion.com/'.($this->flash ? 'swf' : 'embed').'/video/'.preg_replace('#[^a-z\d]#i', '', $id);
	}

	/**
	 * @todo indiedb
	 *
	 * Processes ModDB videos
	 */
	protected function moddb(){
		$id = $this->host === 'moddb.com' && strpos('http://www.moddb.com/media/', $this->content) === 0
			? $this->url['path']
			: $this->content;

		return 'http://www.moddb.com/media/'.($this->flash ? 'embed/' : 'iframe/').preg_replace('/[^\d]/', '', $id);
	}

	/**
	 * Processes HTML5 video
	 */
	protected function video(){
		// @todo check video...
		return $this->content;
	}

	/**
	 * Processes Vimeo videos
	 */
	protected function vimeo(){
		// since the video id is the only numeric part in a common vimeo share url, we can safely strip anything which is not number
		$id = preg_replace('/[^\d]/', '', $this->content);

		// @todo collect & batch request
		$response = $this->fetch('https://api.vimeo.com/videos/'.$id, ['access_token' => $this->parserOptions->vimeo_access_token])->json;

		// @codeCoverageIgnoreStart
		// access token needed - no coverage
		if(isset($response->link)){
			// @todo add fancyness
			var_dump($response);
			return $this->flash ? 'https://vimeo.com/moogaloop.swf?clip_id='.$id : 'https://player.vimeo.com/video/'.$id;
		}
		// @codeCoverageIgnoreEnd

		return '';
	}

	/**
	 * Processes YouTube videos
	 */
	protected function youtube(){

		switch($this->host){
			case 'youtube.com':
				parse_str($this->url['query'], $q);
				$id = $q['v'];
				break;
			case 'youtu.be':
				$id = $this->url['path'];
				break;
			default:
				$id = $this->content;
				break;
		}

		// check video (and get data)
		$params = [
			'id' => $id,
			'part' => 'snippet',
			'key' => $this->parserOptions->google_api_key,
		];

		$response = $this->fetch('https://www.googleapis.com/youtube/v3/videos', $params)->json;

		// @codeCoverageIgnoreStart
		// api key needed - no coverage
		if(isset($response->items) && is_array($response->items) && $response->items[0]->id === $id){
			// @todo support playlists
			var_dump($response);
			return 'https://www.youtube.com/'.($this->flash ? 'v/' : 'embed/').preg_replace('/[^a-z\d-_]/i', '', $id);
		}
		// @codeCoverageIgnoreEnd

		return '';
	}

}
