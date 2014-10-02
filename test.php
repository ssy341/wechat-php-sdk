<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014-09-28
 * Time: 17:58
 */

$operate = $_GET["a"];

if ($operate === "histroy") {
    include("util/history.php");
    $content = getHistoryInfo();
} else if ($operate === "translate") {
    include("util/translate.php");
    $content = getTranslateInfo("hello");
}else if($operate === "xiaoi"){
    include("util/xiaoi.php");
    $keyword =  $_GET["b"];
    $content = getXiaoInfo("api-sieezeyr",$keyword);
}else if($operate === "g"){
    include("util/google.php");
    $keyword =  $_GET["q"];
    $content = getGoogle($keyword);
    $num = count($content);
    echo $num;
    for($i=0;$i<$num;++$i){
        echo $content[$i].'<br />';
    }
    exit(0);
}else if($operate === "everyday"){
    include("util/dailysentence.php");
    echo "进入方法";
    $content = getEverday();
}
echo time();
echo $content;






