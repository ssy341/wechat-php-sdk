<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 14-9-27
 * Time: 上午10:11
 * To change this template use File | Settings | File Templates.
 */
function getTranslateInfo($keyword){
    if($keyword === ""){
        return "要翻译的内容是什么？";
	}
    $apihost = "http://fanyi.youdao.com/";
    $apimethod = "openapi.do?";
    $apiparams = array('keyfrom'=>"thxopen",'key'=>"1332083506",'type'=>"data",'doctype'=>"json",'version'=>"1.1",'q'=>$keyword);
    $apicallurl = $apihost.$apimethod.http_build_query($apiparams);
    //    http://fanyi.youdao.com/openapi.do?keyfrom=thxopen&key=1332083506&type=data&doctype=json&version=1.1&q=hello
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$apicallurl);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $output = curl_exec($ch);
    if(curl_errno($ch)){
        $result = 'curl error code:'.curl_errno($ch).',reason:'.curl_error($ch);
    }
    curl_close($ch);
    $result = "";
    $youdao = json_decode($output,true);
    $errorcode = $youdao['errorCode'];
    $result .= "查询[".$youdao['query']."]\n";
    switch($errorcode){
      case 0:
         if(isset($youdao['basic'])){
//              $result .= isset($youdao['basic']);
//              $result .= $youdao['basic']['phonetic']."<br/>";

             if(isset($youdao['basic']['uk-phonetic'])){
                 $result .= 'uk-英标:'.$youdao['basic']['uk-phonetic']."\n";
             }else{
                 $result .= $youdao['basic']['phonetic']."\n";
             }
             if(isset($youdao['basic']['uk-phonetic'])){
                 $result .= 'us-英标:'.$youdao['basic']['us-phonetic']."\n";
             }
              foreach ($youdao['basic']['explains'] as $value){
                  $result .= $value."\n";
              }
         }else{
              $result .= $youdao['translation'][0] ;
         }
          $result .= "\n以上结果由有道提供";
          break;
      default:
          $result = "系统错误：错误代码:".$errorcode;
          break;
    }
    return trim($result);
}
?>