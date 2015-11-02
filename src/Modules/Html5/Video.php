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

use chillerlan\bbcode\BBTemp;
use chillerlan\bbcode\Modules\ModuleInterface;
use chillerlan\bbcode\Modules\Html5\Html5BaseModule;

/**
 * Transforms several video tags into HTML5
 */
class Video extends Html5BaseModule implements ModuleInterface{

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
	private $_host;

	/**
	 * temp url
	 *
	 * @var array
	 */
	private $_url;

	/**
	 * temp video url
	 *
	 * @var string
	 */
	private $_video_url;

	/**
	 * temp flash
	 *
	 * @var string
	 */
	private $_flash;

	/**
	 * Transforms the bbcode, called from BaseModuleInterface
	 *
	 * @return string a transformed snippet
	 * @see \chillerlan\bbcode\Modules\BaseModuleInterface::transform()
	 * @internal
	 */
	public function _transform(){
		$this->_flash = $this->get_attribute('flash');
		$provider = $this->_get_provider();
		call_user_func([$this, $provider]);

		if($provider === 'video'){
			return '<video src="'.$this->_video_url.'"'.$this->get_css_class('bb-video').' preload="auto" controls="true"></video>';
		}
		else{
			$object = '<iframe src="'.$this->_video_url.'" allowfullscreen></iframe>';

			if($this->_flash){
				$object = '<object type="application/x-shockwave-flash" data="'.$this->_video_url.'">'
					.'<param name="allowfullscreen" value="true">'
					.'<param name="wmode" value="opaque" />'
					.'<param name="movie" value="'.$this->_video_url.'" />'
					.'</object>';
			}

			return '<div'.$this->get_css_class('bb-video').'>'.$object.'</div>';
		}
	}

	/**
	 * Gets the video provider
	 *
	 * @return string
	 */
	private function _get_provider(){
		$this->_url = parse_url($this->content);
		$this->_host = isset($this->_url['host']) ? str_replace('www.', '', $this->_url['host']) : false;
		$_bbtag = $this->bbtag();

		switch(true){
			case $this->tag === 'vimeo' || $_bbtag === 'vimeo' || $this->_host === 'vimeo.com':
				return 'vimeo';
			case $this->tag === 'youtube' || $_bbtag === 'youtube' || in_array($this->_host, ['youtube.com', 'youtu.be']):
				return 'youtube';
			case $this->tag === 'moddb' || $_bbtag === 'moddb' || $this->_host === 'moddb.com':
				return 'moddb';
			case $this->tag === 'dmotion' || $_bbtag === 'dmotion' || in_array($this->_host, ['dailymotion.com', 'dai.ly']):
				return 'dmotion';
			default:
				return 'video';
		}
	}

	/**
	 * Processes Daily Motion videos
	 */
	private function dmotion(){

		switch($this->_host){
			case 'dailymotion.com':
				$id = explode('_', str_replace('/video/', '', $this->_url['path']), 2)[0];
				break;
			case 'dai.ly':
				$id = $this->_url['path'];
				break;
			default:
				$id = $this->content;
				break;
		}

		$this->_video_url = 'http://www.dailymotion.com/'.($this->_flash ? 'swf' : 'embed').'/video/'.preg_replace('#[^a-z\d]#i', '', $id);
	}

	/**
	 * Processes ModDB videos
	 */
	private function moddb(){
		$id = $this->_host === 'moddb.com' && strpos('http://www.moddb.com/media/', $this->content) === 0
			? $this->_url['path']
			: $this->content;

		$this->_video_url = 'http://www.moddb.com/media/'.($this->_flash ? 'embed/' : 'iframe/').preg_replace('/[^\d]/', '', $id);
	}

	/**
	 * Processes HTML5 video
	 */
	private function video(){
		// todo: check video...
		$this->_video_url = $this->content;
	}

	/**
	 * Processes Vimeo videos
	 */
	private function vimeo(){
		// since the video id is the only numeric part in a common vimeo share url, we can safely strip anything which is not number
		$this->_video_url = 'https://'.($this->_flash ? 'vimeo.com/moogaloop.swf?clip_id=' : 'player.vimeo.com/video/').preg_replace('/[^\d]/', '', $this->content);
	}

	/**
	 * Processes YouTube videos
	 */
	private function youtube(){

		// check the video id if needed: http://gdata.youtube.com/feeds/api/videos/[VIDEO_ID]
		// todo: support playlists
		switch($this->_host){
			case 'youtube.com':
				parse_str($this->_url['query'], $q);
				$id = $q['v'];
				break;
			case 'youtu.be':
				$id = $this->_url['path'];
				break;
			default:
				$id = $this->content;
				break;
		}

		// clean out any suspect characters -> #[^a-z\d-_]#i
		$this->_video_url = 'https://www.youtube.com/'.($this->_flash ? 'v/' : 'embed/').preg_replace('/[^a-z\d-_]/i', '', $id);
	}

}
