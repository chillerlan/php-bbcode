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

		$video_url   = $this->getVideoURL();
		$this->flash = $this->getAttribute('flash');
		$cssclass    = ['bb-video'];

		if($this->getAttribute('wide')){
			$cssclass[] = 'wide';
		}

		$cssclass = $this->getCssClass($cssclass);

		if($video_url === false){
			return '<video src="'.$this->content.'"'.$cssclass.' preload="auto" controls="true"></video>';
		}
		else{

			if(!empty($video_url)){
				$player = '<iframe src="'.$video_url.'" allowfullscreen></iframe>';

				if($this->flash){
					$player = '<object type="application/x-shockwave-flash" data="'.$video_url.'">'
					          .'<param name="allowfullscreen" value="true">'
					          .'<param name="wmode" value="opaque" />'
					          .'<param name="movie" value="'.$video_url.'" />'
					          .'</object>';
				}

				return '<div'.$cssclass.'>'.$player.'</div>';

			}

			return '';
		}
	}

	/**
	 * Gets the video provider
	 *
	 * @return string
	 */
	protected function getVideoURL(){
		$bbtag = $this->bbtag();
		$url   = parse_url($this->content);
		$host  = isset($url['host']) ? str_replace('www.', '', $url['host']) : false;

		// Process Vimeo videos
		if($this->tag === 'vimeo' || $bbtag === 'vimeo' || $host === 'vimeo.com'){

			// since the video id is the only numeric part in a common vimeo share url, we can safely strip anything which is not number
			$id = preg_replace('/[^\d]/', '', $this->content);

			// @todo collect & batch request
			$response = $this->fetch('https://api.vimeo.com/videos/'.$id, ['access_token' => $this->parserOptions->vimeo_access_token])->json;

			// access token needed - no coverage
			// @codeCoverageIgnoreStart
			if(isset($response->link)){
				// @todo add fancyness
				return $this->flash ? 'https://vimeo.com/moogaloop.swf?clip_id='.$id : 'https://player.vimeo.com/video/'.$id;
			}
			// @codeCoverageIgnoreEnd

			return '';
		}

		// Process YouTube videos
		else if($this->tag === 'youtube' || $bbtag === 'youtube' || in_array($host, ['youtube.com', 'youtu.be'])){

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
					'id' => $id,
					'part' => 'snippet',
					'key' => $this->parserOptions->google_api_key,
				];

				$response = $this->fetch('https://www.googleapis.com/youtube/v3/videos', $params)->json;

				// api key needed - no coverage
				// @codeCoverageIgnoreStart
				if(isset($response->items, $response->items[0]) && $response->items[0]->id === $id){
					// @todo support playlists
					return 'https://www.youtube.com/'.($this->flash ? 'v/' : 'embed/').preg_replace('/[^a-z\d-_]/i', '', $id);
				}
				// @codeCoverageIgnoreEnd
			}

			return '';
		}

		//  Process ModDB videos @todo indiedb
		else if($this->tag === 'moddb' || $bbtag === 'moddb' || $host === 'moddb.com'){

			$id = $host === 'moddb.com' && strpos('http://www.moddb.com/media/', $this->content) === 0
				? $url['path']
				: $this->content;

			return 'http://www.moddb.com/media/'.($this->flash ? 'embed/' : 'iframe/').preg_replace('/[^\d]/', '', $id);
		}

		// Process Daily Motion videos
		else if($this->tag === 'dmotion' || $bbtag === 'dmotion' || in_array($host, ['dailymotion.com', 'dai.ly'])){

			if($host === 'dailymotion.com'){
				$id = explode('_', str_replace('/video/', '', $url['path']), 2)[0];
			}
			else if($host === 'dai.ly'){
				$id = $url['path'];
			}
			else{
				$id = $this->content;
			}

			return 'http://www.dailymotion.com/'.($this->flash ? 'swf' : 'embed').'/video/'.preg_replace('#[^a-z\d]#i', '', $id);
		}

		// Process HTML5 video
		else{
			// @todo check video...
			return false;
		}

	}

}
