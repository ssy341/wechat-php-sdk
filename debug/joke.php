<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/10/17 0017
 * Time: 23:27
 */

//include("../util/joke.php");
//echo time();
//fetchJoke();
//echo time();
test();
function test(){
//    $url = "http://i2.xiaohua.fd.zol-img.com.cn/t_s300x2000/g3/M06/0F/05/Cg-4WFRIywiIaWoTAAxB5fdTQf4AAQauQGejaQADEH9538.gif";
    $url = "ht";
    echo match($url);
}

function match($str){
    $regex = "/[a-z]+:\/\/[a-z0-9_\-\/.%]+/i";
    $result = preg_match($regex,$str);
    return $result;
}