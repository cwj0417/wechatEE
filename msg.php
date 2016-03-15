<?php
class msg
{
	private $access_token, $util, $url, $timeout = 300;
	public $touser, $toparty, $totag, $msgtype = 'text', $agentid = 1;
	function init_access_token($token) {
		$this->access_token = $token;
		$this->url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token={$this->access_token}";
	}
	function init_util($util) {
		$this->util = $util;
	}
	function init_cache($cache) {
		$this->cache = $cache;
	}
	function settimeout($s) {
		$this->timeout = $s;
	}
	function checkCache($cacheKey) {
		if($cacheKey == false || property_exists($this, 'cache')) {
			return true;
		}else {
			if($this->cache->get($cacheKey)) {
				return false;
			}else {
				$this->cache->set($cacheKey, '1');
				$this->cache->expireat($cacheKey, time() + $this->timeout);
				return true;
			}
		}
	}
	function setuser($s) {
		if(is_array($s)) {
			$s = implode('|', $s);
		}
		$this->touser = $s;
		return $this;
	}
	function setparty($s) {
		if(is_array($s)) {
			$s = implode('|', $s);
		}
		$this->toparty = $s;
		return $this;
	}
	function settag($s) {
		if(is_array($s)) {
			$s = implode('|', $s);
		}
		$this->totag = $s;
		return $this;
	}
	function setagentid($s) {
		if(is_numeric($s)) {
			$this->agentid = $s;
			return $this;
		}else {
			return false;
		}
	}
	function sendText($txt, $tag = '', $cacheKey = false) {
		if(!$this->checkCache($cacheKey)) {
			return;
		}
		if(is_array($tag)) {
			$tag = implode('', array_map(function($a) {return '[' . $a . ']';}, $tag));
		}
		$msg = [];
		foreach (['touser','toparty','totag','agentid','msgtype'] as $attr) {
			if($this->$attr != null) {
				$msg[$attr] = $this->$attr;
			}
		}
		$msg['text'] = ['content'=> $tag . $txt];
		$res = $this->util->httpRequest($this->url, json_encode($msg, JSON_UNESCAPED_UNICODE));
		return $res;
	}
	function sendNews($arr, $cacheKey = false) {
		$this->msgtype = 'news';
		foreach (['touser','toparty','totag','agentid','msgtype'] as $attr) {
			if($this->$attr != null) {
				$msg[$attr] = $this->$attr;
			}
		}
		$msg['news'] = ['articles'=> $arr];
		if(!$this->checkCache($cacheKey)) {
			return;
		}
		$res = $this->util->httpRequest($this->url, json_encode($msg, JSON_UNESCAPED_UNICODE));
		return $res;
	}
	static function newsEntity($tag,$title, $description, $url, $picurl) {
		if(is_array($tag)) {
			$tag = implode('', array_map(function($a) {return '[' . $a . ']';}, $tag));
		}
		return ['title'=>$tag . $title, 'description'=>$description, 'url'=>$url, 'picurl'=> $picurl];
	}
}