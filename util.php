<?php
class util
{
	public function httpRequest($url, $post_string, $method = "post", $connectTimeout = 1000, $readTimeout = 2000)
    {
        $method = strtolower($method);
        if ($post_string != null && $method == "get") {
            $url = $url . "?" . $post_string;
        }
        $result = "";
        if (function_exists('curl_init')) {
            $timeout = $connectTimeout + $readTimeout;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connectTimeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            if ($method == "post") {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
            } else if ($method == "delete") {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'API PHP5 Client (curl) ' . phpversion());
            $result = curl_exec($ch);
            if (isset($_GET['t']) && $_GET["t"] == 1) {
                var_dump($result, $url);
            }
            curl_close($ch);
        } else {
            // Non-CURL based version...
        }
        return $result;
    }
}