<?php
interface cache_interface
{
	function init($config, $prefix);
	function set($key);
	function get($key);
	function expireat($key, $time);
}
class cache
{
	function getClass($type, $config, $prefix) {
		if(class_exists($class = "cache_" . $type)) {
			$cacheObj = new $class();
			if($cacheObj->init($config, $prefix)) {
				return $cacheObj;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
}
class cache_redis implements cache_interface
{
	private $conn, $prefix;
	function init($config, $prefix) {
		$this->prefix = $prefix;
		$this->conn = new Redis();
		if($this->conn->connect($config['host'], $config['port'])) {
			return true;
		}else {
			return false
		}
	}
	function set($key, $value) {
		return $this->conn->set($key, $value);
	}
	function get($key) {
		return $this->conn->get($key);
	}
	function expireat($key, $time) {
		return $this->conn->expireAt($key, $time);
	}
}