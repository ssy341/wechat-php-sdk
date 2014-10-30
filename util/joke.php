<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/10/15 0015
 * Time: 7:44
 */

$action = "";
if(isset($_GET['action'])){
    $action = $_GET['action'];
}
if('fetchJoke' == $action){
    $url = $_GET['url'];
    $t = $_GET['t'];
    if('fetchText' == $t){
        if(isset($url)){
            echo fetchJoke($url);
        }else{
           echo fetchJoke();
        }
    }else{
        if(isset($url)){
            echo  fetchPicJoke($url);
        }else{
            echo  fetchPicJoke();
        }
    }
}
if('addJoke' == $action){
    $title = $_POST['title'];
    $content = $_POST['content'];
    if(isset($content)){
        addJoke($title,$content);
        echo $title."<br/>".$content;
    }else{
        echo "内容不能为空";
    }
}
/**
 * 抓图笑话
 * @return string
 */
function fetchPicJoke($url = "http://xiaohua.zol.com.cn/qutu/"){
    include_once('../thirdparty/simple_html_dom.php');
    $table = "";
    try{
        $html = new simple_html_dom();
        $html->load_file($url); //put url or filename in place of xxx
        if(!isset($html)){
            $html->clear();
            return "获取失败，请联系开发人员";
        }else{
            $table .=  "<table class='table'><thead><th>标题</th><th>内容</th><th>操作</th></thead><tbody>";
            foreach($html->find('ul[class=article-list] li') as $items){
                //标题
                $imgtitle = $items->find('span[class=article-title] a',0)->plaintext;
                $imgtitle =  iconv('gbk', 'utf-8', $imgtitle);
                //图片地址
                $imgloadsrc =  $items->find('div[class=summary-text] p a img',0);
                $imgsrc = $items->find('div[class=summary-text] p a img',0)->src;
                if($imgsrc==''){
                    $tmpSrc = $imgloadsrc->loadsrc;
                    $len = strlen($tmpSrc);
                    $id = substr($tmpSrc,$len-($len-70),$len-($len-75));
                    $table .= "<tr><td>".$imgtitle."</td><td><img src='".$tmpSrc."'/></td><td>"
                    ."<button onclick='addPicJoke(this)'>添加</button></td></tr>";
                }else{
                    $len = strlen($imgsrc);
                    $id = substr($imgsrc,$len-($len-70),$len-($len-75));
                    $table .= "<tr><td>".$imgtitle."</td><td><img src='".$imgsrc."'/></td><td>"
                        ."<button onclick='addPicJoke(this)'>添加</button></td></tr>";
                }
            }
            $table .= "</tbody></table>";
        }
    }catch (Exception $e){
        echo $e->getMessage();
    }
    return $table;
}


/**
 * 抓取文字笑话
 */
function fetchJoke($url='http://xiaohua.zol.com.cn/new/'){
    include_once('../thirdparty/simple_html_dom.php');
    $table = "";
    try{
        $html = new simple_html_dom();
        $html->load_file($url); //put url or filename in place of xx
       if(!isset($html)){
            $html->clear();
            return "获取失败，请联系开发人员";
        }else{
           $table .=  "<table class='table'><thead><th>标题</th><th>内容</th><th>操作</th></thead><tbody>";
            foreach ($html->find('ul[class=article-list] li') as $items) {
                $joketitle = $items->find('span[class=article-title] a', 0)->plaintext;
                $jokecontent = $items->find('div[class=summary-text]', 0)->plaintext;
                //改变字符集
                $joketitle =  iconv('gbk', 'utf-8', $joketitle);
                $jokecontent =  iconv('gbk', 'utf-8', $jokecontent);
                $table .= "<tr><td>".$joketitle."</td><td>".$jokecontent."</td><td>"
                    ."<button onclick='addTextJoke(this)'>添加</button></td></tr>";
            }
           $table .= "</tbody></table>";
        }
    }catch (Exception $e){
        return $e->getMessage();
    }
    return $table;
}

/**
 * 添加文字笑话
 * @param $title
 * @param $content
 * @return string
 */
function addJoke($title,$content){
    try{
         include_once('../db/DBUtil.php');
         $DB = new DBUtil('../nav/sqlite/manger/joke');
         $DB->query("insert into joke (title,content) VALUES ('$title','$content')");
         $DB->del();
    }catch (Exception $e){
        return $e->getMessage();
    }
}

/**
 * 获取笑话
 * @param $openid
 * @return array
 */
function getJoke($openid)
{
    include_once('db/DBUtil.php');
    $sql = "select * from (select id,title,content from joke where id not in (select jokeid from record where openid = '$openid')) t order by RANDOM() limit 1";
    $DB = new DBUtil('db/joke');
    $result = $DB->query($sql);
    $joke = null;
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $joke = new Joke(htmlspecialchars($row['id']),htmlspecialchars($row['title']), htmlspecialchars($row['content']));
    }
    $DB->del();
    return $joke;
}

/**
 * 存储用户获取笑话记录
 * @param $openid
 * @param $jokeid
 */
function record($openid,$jokeid){
    $sql = "insert into record (openid,jokeid) VALUES ('$openid','$jokeid')";
    $DB = new DBUtil('../nav/sqlite/manger/joke');
    $DB->query($sql);
    $DB->del();
}

class Joke{
    public $title;
    public $content;
    public $id;
    function __construct($id, $title,$content)
    {
        $this->content = $content;
        $this->title = $title;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}