<?php 
define('DS', DIRECTORY_SEPARATOR);
define('BASEDIR',__DIR__ . DS);
ini_set('display_errors', 'on');
error_reporting(E_ALL);
function __autoload($cls)
{
	$file = BASEDIR . $cls . ".php";
	file_exists($file) && require $file;
}
class wechatEE
{
	private $corpid, $corpsecret, $attrs = [], $clses = [];
	function __construct($corpid, $corpsecret) {
		$this->corpid = $corpid;
		$this->corpsecret = $corpsecret;
		$this->access_token = $this->getToken();
		$this->extend('msg', 'access_token');
		$this->extend('number', 'access_token');
		$this->extend('msg', 'util');
		$this->extend('number', 'util');
	}
	function __get($attr) {
		if(class_exists($attr)) {
			$this->$attr = new $attr();
		}else {
			$this->$attr = '';
		} 
		return $this->$attr;
	}
	function extend($cls, $attr) {
		if(!property_exists($this, $cls)) {
			$this->$cls = new $cls();
		}
		if(property_exists($this, $attr) && method_exists($this->$cls, $method = "init_" . $attr)) {
			$this->$cls->$method($this->$attr);
			if(!in_array($cls, $this->clses)) {
				array_push($this->clses, $cls);
			}
			if(!in_array($attr, $this->attrs)) {
				array_push($this->attrs, $attr);
			}
		}
	}
	function initCache($type, $config, $prefix = 'wechatee') {
		$factory = new cache();
		if($cacheObj = $factory->getClass($type, $config, $prefix)) {
			$this->cache = $cacheObj;
			foreach ($this->clses as $cls) {
				$this->extend($cls, 'cache');
			}
			return true;
		}else {
			return false;
		}
	}
	function getToken() {
		if($this->access_token == null) {
			$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken";
			$params = "corpid={$this->corpid}&corpsecret={$this->corpsecret}";
			$res = json_decode($this->util->httpRequest($url, $params,'get'), true);
			if(isset($res['access_token'])){
				$this->access_token = $res['access_token'];
			}else{
				var_dump('获取accesstoken失败'.$res);
				$this->access_token = false;
			}
		}
		return $this->access_token;
	}
}