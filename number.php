<?php
class number
{
	private $access_token, $util;
	public $touser, $toparty, $totag, $msgtype = 'text', $agentid = 1;
	function init_access_token($token) {
		$this->access_token = $token;
	}
	function init_util($util) {
		$this->util = $util;
	}
}