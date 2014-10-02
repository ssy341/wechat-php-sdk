<?php
/**
 * 微信公众平台 PHP SDK 示例文件
 *
 * @author NetPuter <netputer@gmail.com>
 */

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
      $this->responseText('欢迎关注');
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
    protected function onText() {
        //匹配前两个字符时fy的为翻译操作
        $_fy_operate = "fy";
        $_history_today = "history";
        $_google = "g";
        $_daily = "everyday";
        /*if( $this->getRequest('content') === '蚂蚁上树')
            //$this->responseText('收到了文字消息：' . $this->getRequest('content'));
            $this->responseText('我不会做蚂蚁上树');
        else if($this->getRequest('content') === '卢灿权')
             $this->responseText('卢灿权王八蛋');
        else if($this->getRequest('content') === '人品')
        	$this->renpin($this->getRequest('content'));
        else
        	$this->responseText('收到了文字消息：' . $this->getRequest('content'));
			*/
        $keyword = trim($this->getRequest('content'));
//        $this->responseText($keyword);
//        $content = "";
        if("help" === $keyword){
            $content = "欢迎使用小扇子robot！\n "
                      ."发送下列关键字可以获取更多资讯：\n"
                      ."》everyday 金山词霸每日一句\n"
                      ."》history 历史上的今天\n"
                ."》fy+你要翻译的词语或句子 比如：fyhello\n"
                ."更多功能持续更新中。。。  :-)\n";
            $this->responseText($content);
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
        }else{
            //include("util/xiaoi.php");
            //$content = getXiaoInfo("api-sieezeyr",$keyword);
            $content = "主人还没给我设置这类话题的回复，你帮我悄悄的告诉他吧~";
            $this->responseText($content);
        }

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
     // $num = 1 / 0;
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
