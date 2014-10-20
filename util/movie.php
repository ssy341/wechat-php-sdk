<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/10/10 0010
 * Time: 7:45
 */

function getMovie(){
    include_once('thirdparty/simple_html_dom.php');
    $url = "http://theater.mtime.com/China_Guangdong_Province_Guangzhou_Liwan/4404/";
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
//    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $output = curl_exec($ch);
//    echo $output;

    try{
        $html = str_get_html($output); //put url or filename in place of xxx
        if(!isset($html)){
            $html->clear();
            return "获取失败，请联系开发人员";
        }else{
           // $temp =  $html->find('meta[name=description]', 0)->content;
           // return trim( $temp);
//            $items = $html->find('div[id=movieItemListRegion]',0);
//            foreach($items->find('b') as $item){
//                echo $item->plaintext;
//            }
            /*foreach($html->find('script') as $item){
                echo $item->plaintext;
            }*/
            //echo $html->find('script',0);
            curl_close($ch);
            echo '1231241324';
        }
    }catch (Exception $e){
        return $e->getMessage();
    }
}

