<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014-09-28
 * Time: 15:43
 */

function getEverday(){
    include_once('thirdparty/simple_html_dom.php');
    $url = "http://news.iciba.com/dailysentence";
    try{
        $html = new simple_html_dom();
        $html->load_file($url); //put url or filename in place of xxx
        if(!isset($html)){
            $html->clear();
            return "获取失败，请联系开发人员";
        }else{
            $temp =  $html->find('meta[name=description]', 0)->content;
            return trim( $temp);
        }
    }catch (Exception $e){
        return $e->getMessage();
    }
}
