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
 * @todo
 */
class Video extends Html5BaseModule implements ModuleInterface{

	/**
	 * @var array
	 */
	protected $tags = ['video', 'dmotion', 'vimeo', 'youtube', 'moddb'];

	/**
	 * @var
	 */
	private $_host;

	/**
	 * @var
	 */
	private $_url;

	/**
	 * @var
	 */
	private $_video_url;

	/**
	 * @var
	 */
	private $_flash;

	/**
	 * Returns the processed bbcode
	 *
	 * @return string a HTML snippet
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
	 *
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
	 *
	 */
	private function moddb(){
		$id = $this->_host === 'moddb.com' && strpos('http://www.moddb.com/media/', $this->content) === 0
			? $this->_url['path']
			: $this->content;

		$this->_video_url = 'http://www.moddb.com/media/'.($this->_flash ? 'embed/' : 'iframe/').preg_replace('/[^\d]/', '', $id);
	}

	/**
	 *
	 */
	private function video(){
		// todo: check video...
		$this->_video_url = $this->content;
	}

	/**
	 *
	 */
	private function vimeo(){
		// since the video id is the only numeric part in a common vimeo share url, we can safely strip anything which is not number
		$this->_video_url = 'https://'.($this->_flash ? 'vimeo.com/moogaloop.swf?clip_id=' : 'player.vimeo.com/video/').preg_replace('/[^\d]/', '', $this->content);
	}

	/**
	 *
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
