<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014-09-28
 * Time: 17:58
 */

$operate = $_GET["a"];
$content = "";
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
}else if($operate === 'json'){
    include("./nav/fetchLinks.php");
    $obj = new Link('123','456','17');
    $content = json_encode($obj);
}else if($operate === 'movie'){
    include("util/movie.php");
    getMovie();
}else if($operate === 'joke'){
    include("util/joke.php");
//    $content = joke();
//    $content = j();
    fetchJoke();
}else if($operate === 'mail'){
    include("../util/mail.php");
    $result = send_mail_lazypeople('keithssy@sina.com','通知','你有新的消息');
}

echo $result;
echo time();
echo $content;






