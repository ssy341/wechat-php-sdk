<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014-09-28
 * Time: 15:43
 */

function getHistoryInfo(){
    include_once('thirdparty/simple_html_dom.php');
    $url = "http://www.todayonhistory.com/";
    try{
        $html_analysis = file_get_html($url);
        if(!isset($html_analysis)){
            $html_analysis->clear();
            return "获取失败，请联系开发人员";
        }else{
            $contentStr = "历史上的：".date("m")."月".date("d")."日：\n";
            foreach($html_analysis->find('div[class="main"] ul[class="list clearfix"] li') as $item){
                $contentStr .= str_replace(date("m")."月".date("d")."日","",$item->plaintext)."\n";
                if(strlen($contentStr) > 2000){ break;}
            }
            $html_analysis->clear();
            return trim($contentStr);
        }
    }catch (Exception $e){
        return $e->getMessage();
    }
}