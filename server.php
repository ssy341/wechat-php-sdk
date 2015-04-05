<?php
/**
 * 微信公众平台 PHP SDK 示例文件
 *
 * @author NetPuter <netputer@gmail.com>
 */

$help = "欢迎使用小扇子robot！\n "
    ."发送下列关键字可以获取更多资讯：\n"
    ."》【e】金山词霸每日一句（set1）\n"
    //."》history 历史上的今天(暂时有问题，解决中)\n"
    ."》【hello】你要翻译的词语或句子（要在英语模式下） 比如：hello，如果需要发音发送：hellosv\n"
    ."》【笑话】来一个笑话（要在无敌模式下）\n"
    ."》【set1英语模式，set0无敌模式】"
    ."更多功能持续更新中。。。  :-)\n";

require('util/Wechat.php');



/**
 * 微信公众平台演示类
 */
class MyWechat extends Wechat {

    /**
     * 用户关注时触发，回复「欢迎关注」
     *
     * @return void
     */
    protected function onSubscribe() {
        global $help;
        $this->responseText($help);
    }

    /**
     * 用户取消关注时触发
     *
     * @return void
     */
    protected function onUnsubscribe() {
        // 「悄悄的我走了，正如我悄悄的来；我挥一挥衣袖，不带走一片云彩。」
    }

    /**
     * 收到文本消息时触发，回复收到的文本消息内容
     *
     * @return void
     */
    protected function onText()
    {
        //获取用户发送的文字
        $keyword = trim($this->getRequest('content'));
        //获取用户的uuid
        $openid = $this->getRequest('fromusername');
        /*
           * funid 定义
           * 英语模式 1
           * 万能模式 0
           */
        $_fn_english = "1";
        $_fn_god = "0";

        //查询当前模式
        include_once('./db/DBUtil.php');
        $sql = "select fun from function where userid = '$openid'";
        $DB = new DBUtil('./db/sqlite/admin.sqlite3');
        $result = $DB->query($sql);
        $funidflag = "";
        while ($res = $result->fetchArray()) {
            if (isset($res['fun'])) {
                $funidflag = $res['fun'];
            }
        }

        //设置模式
        if ('set' === substr($keyword, 0, 3)) {
            $funid = substr($keyword, 3, 1);
            $funid = trim($funid);
            if (strlen($funid) > 0) {
                if ($funidflag != "") {
                    $update = "update function set fun = '$funid' where userid = '$openid'";
                    $result = $DB->query($update);
                } else {
                    $insert = "insert into function (userid,fun) values('$openid','$funid ')";
                    $result = $DB->query($insert);
                }
            } else {
                //默认设置万能模式
                if ($funidflag != "") {
                    $update = "update function set fun = '0' where userid = '$openid'";
                    $result = $DB->query($update);
                } else {
                    $insert = "insert into function (userid,fun) values('$openid','0 ')";
                    $result = $DB->query($insert);
                }
            }
            $DB->del();
            $this->responseText("设置成功，当前功能为：$funid ");
        }

        //学英语模式
        if ($funidflag === $_fn_english) {
            include("util/translate.php");
            //如果是直接向得到发音
            $keywordsv = substr($keyword,0,strlen($keyword)-2);
            //金山词霸每日英语
            if ("e" === $keyword) {
                include("util/dailysentence.php");
                $content = getEverday();
                $this->responseText($content);
            } else if ("v" === $keyword) {
                //回复语音（已经查询过的单词）
                //http://weixin.thxopen.com/db/phpliteadmin.php
                //发送语音
                $sql = "select value from cache where key = 'looked'";
                $result = $DB->query($sql);
                $lookedword = "";
                while ($res = $result->fetchArray()) {
                    if (isset($res['value'])) {
                        $lookedword = $res['value'];
                    }
                }
                $content = getTranslateVoice($lookedword);
                $this->responseMusic($lookedword, $content, 'http://dict.youdao.com/dictvoice?audio=' . $lookedword, 'http://dict.youdao.com/dictvoice?audio=' . $lookedword);
            } else if(substr($keyword,strlen($keyword)-2,2) === 'sv'){
                //直接发送语音
                $content = getTranslateVoice($keywordsv);
                $this->responseMusic($keywordsv,$content,'http://dict.youdao.com/dictvoice?audio='.$keywordsv,'http://dict.youdao.com/dictvoice?audio='.$keywordsv);
            }else if ("r" === $keyword) {
                //回复之前查询过的单词，随机取十个
                //http://weixin.thxopen.com/db/phpliteadmin.php
                $reviewsql = "select * from ( select word,explain from words order by RANDOM() ) t  limit 10";
                $reviewword = [];
                $result = $DB->query($reviewsql);
                while ($res = $result->fetchArray()) {
                    if (isset($res['word'])) {
                        array_push($reviewword, array("explain" => $res['explain']));
                    }
                }
                $content = "";
                for ($i = 0; $i < count($reviewword); $i++) {
                    $tmp = $reviewword[$i]['explain'] . "\n";
                    $contentlen = strlen($content);
                    $tmplen = strlen($tmp);
                    if ($contentlen > 2000 || $contentlen + $tmplen > 2000) {
                        break;
                    } else {
                        $content .= $tmp;
                    }
                }
                $DB->del();
                $content = str_replace("查询[", "", $content);
                $content = str_replace("]", "", $content);
                $content = str_replace("以上结果由有道提供", "----------", $content);
                $this->responseText($content);
            } else {
                //纯文字解释
                $result = $DB->query("select explain from words where word = '$keyword'");
                $haveContent = "";
                while ($res = $result->fetchArray()) {
                    if (isset($res['explain'])) {
                        $haveContent = $res['explain'];
                    }
                }
                if ($haveContent === "") {
                    $content = getTranslateInfo($keyword);
                    $haveContent = $content;
                    //插入到数据库
                    $stmt = $DB->prepare("insert into words (word,explain) values (:word,:explain)");
                    $stmt->bindValue(':word', $keyword, SQLITE3_TEXT);
                    $content = str_replace("查询[", "", $content);
                    $content = str_replace("]", "", $content);
                    $content = str_replace("以上结果由有道提供", "", $content);
                    $stmt->bindValue(':explain', $content, SQLITE3_TEXT);
                    $stmt->execute();

                    //每次查了新词。更新本次查询的单词到"缓存"，以便获取发音
                    $sql = "update cache set value = '$keyword' where key = 'looked'";
                    $DB->query($sql);
                }
                $DB->del();
                $this->responseText($haveContent);
            }
        } else if ($funidflag === $_fn_god) {
            //万能模式
            if ("笑话" === $keyword) {
                include("util/joke.php");
                $openid = $this->getRequest('fromusername');
                $content = getJoke($openid);
                if ($content == null) {
                    $this->responseText("你太厉害了，笑话都看完了，赶快告诉主人，添加笑话吧！");
                }
                $id = $content->getId();
                $title = $content->getTitle();
                $summary = $content->getContent();
                record($openid, $id);
                if ($this->match($summary)) {
                    $items = array(
                        new NewsResponseItem($title, null, $summary, $summary)
                    );
                    $this->responseNews($items);
                } else {
                    $this->responseText($title . "\n" . $summary);
                }
            }

        } else {
            $DB->del();
            if ($funidflag === "") {
                global $help;
                $this->responseText($help);
            } else {
                $content = "主人还没给我设置这类话题的回复，你帮我悄悄的告诉他吧~";
                $this->responseText($content);
            }
        }


        /* if("笑话" === $keyword){
             include("util/joke.php");
             $openid = $this->getRequest('fromusername');
             $content = getJoke($openid);
             if($content == null){
                 $this->responseText("你太厉害了，笑话都看完了，赶快告诉主人，添加笑话吧！");
             }
             $id = $content->getId();
             $title = $content->getTitle();
             $summary = $content->getContent();
             record($openid,$id);
             if($this->match($summary)){
                 $items = array(
                     new NewsResponseItem($title, null, $summary, $summary)
                 );
                 $this->responseNews($items);
             }else{
                 $this->responseText($title."\n".$summary);
             }
         }else{
             include("util/translate.php");
             $content = getTranslateInfo($keyword);
             $this->responseText($content);
         }*/
        /*
        if("help" === $keyword){
            global $help;
            $this->responseText($help);
        }else if(substr($keyword,0,2) === $_fy_operate){
            $content =  $this->fy($keyword);
            $this->responseText($content);
        }else if($keyword === $_history_today ){
            include("util/history.php");
            $content = getHistoryInfo();
            $this->responseText($content);
        }else if(substr($keyword,0,1) === $_google){
            include("util/google.php");
            $content = getGoogle(substr($keyword,1));
            $this->responseNews($content);
        }else if($keyword === $_daily){
            include("util/dailysentence.php");
            $content = getEverday();
            $this->responseText($content);
        }else if($keyword === $_joke){
            include("util/joke.php");
            $openid = $this->getRequest('fromusername');
            $content = getJoke($openid);
            if($content == null){
                $this->responseText("你太厉害了，笑话都看完了，赶快告诉主人，添加笑话吧！");
            }
            $id = $content->getId();
            $title = $content->getTitle();
            $summary = $content->getContent();
            record($openid,$id);
            if($this->match($summary)){
                $items = array(
                    new NewsResponseItem($title, null, $summary, $summary)
                );
                $this->responseNews($items);
            }else{
                $this->responseText($title."\n".$summary);
            }
        }else{
            //include("util/xiaoi.php");
            //$content = getXiaoInfo("api-sieezeyr",$keyword);
            $content = "主人还没给我设置这类话题的回复，你帮我悄悄的告诉他吧~";
            $this->responseText($content);
        }*/

    }

    /**
     * 翻译模块
     * @param $keyword
     */
    protected function fy($keyword){
        include("util/translate.php");
        if(strlen(substr($keyword,2))>0){
            if(substr($keyword,2) === 'i love you'){
                $content = "我知道你很爱我，但是不要表达出来，我会害羞的么~\n如果你仍须翻译此话请发送fyfi love you";
            }else if($keyword === 'fyfi love you'){
                $content = getTranslateInfo('i love you');
            }else{
                $content = getTranslateInfo(substr($keyword,2));
            }
        }else{
            $content = "发送fy+任何字符即可翻译 比如 fyhello world";
        }
        return $content;
    }

    protected function renpin($content){
        $this->responseText('人品不错哦今天！');
    }

    /**
     * 匹配url
     * @param $str
     * @return int
     */
    function match($str){
        $regex = "/[a-z]+:\/\/[a-z0-9_\-\/.%]+/i";
        $result = preg_match($regex,$str);
        return $result;
    }



    /**
     * 收到图片消息时触发，回复由收到的图片组成的图文消息
     *
     * @return void
     */
    protected function onImage() {
        $items = array(
            new NewsResponseItem('标题一', '描述一', $this->getRequest('picurl'), $this->getRequest('picurl')),
            new NewsResponseItem('标题二', '描述二', $this->getRequest('picurl'), $this->getRequest('picurl')),
        );

        $this->responseNews($items);
    }

    /**
     * 收到地理位置消息时触发，回复收到的地理位置
     *
     * @return void
     */
    protected function onLocation() {
        //$num = 1 / 0;
        // 故意触发错误，用于演示调试功能

        $this->responseText('收到了位置消息：' . $this->getRequest('location_x') . ',' . $this->getRequest('location_y'));
    }

    /**
     * 收到链接消息时触发，回复收到的链接地址
     *
     * @return void
     */
    protected function onLink() {
        $this->responseText('收到了链接：' . $this->getRequest('url'));
    }

    /**
     * 收到未知类型消息时触发，回复收到的消息类型
     *
     * @return void
     */
    protected function onUnknown() {
        $this->responseText('收到了未知类型消息：' . $this->getRequest('msgtype'));
    }

}

$wechat = new MyWechat('clboxing', TRUE);
$wechat->run();