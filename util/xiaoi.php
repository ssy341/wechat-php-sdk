<?php

function getXiaoInfo($openid,$content){
	$app_key = "Lo8KNKVNUS9Z";
	$app_secret = "kHKIghU2JIwlP43BU0QS";
	$realm = "xiaoi.com";
	$method = "POST";
	$uri = "/robot/ask.do";
	$nonce = "";
	$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
	for ( $i = 0; $i < 40; $i++) 
		$nonce .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
	$HA1 = sha1($app_key . ":" . $realm . ":" . $app_secret);
	$HA2 = sha1($method . ":" . $uri);
	$sign = sha1($HA1 . ":" . $nonce . ":" . $HA2);

	//接口调用
	$url = "http://nlp.xiaoi.com/robot/ask.do";
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Auth: app_key="'.$app_key.'", nonce="'.$nonce.'",signature="'.$sign.'"'));
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTfields,"question=".urlencode($content)."&userId=".$openid."&platform=custom&type=0");
	$output = curl_exec($ch);
	if($output === FALSE){
		return "cURL Error:".curl_error($ch);
	}
	return trim($output);
}

?>