<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014-09-28
 * Time: 15:43
 */

function getGoogle($keyword){
    include_once('thirdparty/simple_html_dom.php');
    $url = "http://209.85.229.119/";
    $apimethod = "search?";
    $apiparams = array('q'=>$keyword);
    $apicallurl = $url.$apimethod.http_build_query($apiparams);
    $result = array();
    try{
        $html_analysis = file_get_html($apicallurl);
        if(!isset($html_analysis)) {
            $html_analysis->clear();
            return "获取失败，请联系开发人员";
        }else{
            $items = array();
            foreach($html_analysis->find('div[id="ires"] li[class="g"]') as $item){
                $temp = str_get_html($item);
                $href = substr($temp->find("li h3 a",0)->href,7);
                $h3 = $temp->find("li h3 a",0)->plaintext;
                $content = $temp->find("li div",0)->children(1)->plaintext;
                array_push($items,new googleItem($h3,$content,'', $href));
                //if(strlen($result) > 2000){ break;}
                if(count($items) === 2){
                    break;
                }
            }
            $result = $items;
        }
    }catch (Exception $e){
        return $e->getMessage();
    }
    return $result;
}

class googleItem {

    protected $title;
    protected $description;
    protected $picUrl;
    protected $url;

    protected $template =
        '<item>
          <Title><![CDATA[%s]]></Title>
          <Description><![CDATA[%s]]></Description>
          <PicUrl><![CDATA[%s]]></PicUrl>
          <Url><![CDATA[%s]]></Url>
        </item>';

    public function __construct($title, $description, $picUrl, $url) {
        $this->title = $title;
        $this->description = $description;
        $this->picUrl = $picUrl;
        $this->url = $url;
    }

    public function __toString() {
        return sprintf($this->template,
            $this->title,
            $this->description,
            $this->picUrl,
            $this->url
        );
    }

}