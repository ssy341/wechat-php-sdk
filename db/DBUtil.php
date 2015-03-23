<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/10/17 0017
 * Time: 23:41
 */
class DBUtil
{
    private $db;
    private $url;

    function __construct($url)
    {
        $this->url = $url;
        $this->db = $this->getConn();
    }

    private function getConn()
    {
        try {
            $db = new SQLite3($this->getUrl());
            if (!$db) {
                echo "<h2>Couldn't open the database!</h2>";
                die();
            }
        } catch (Exception $e) {
            echo $e;
        }
        return $db;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * 执行sql
     * @param $sql  sql语句
     */
    public function query($sql)
    {
        return $this->db->query($sql);
    }

    /**
     * 执行sql
     * @param $sql  sql语句
     */
    public function prepare($sql)
    {
        return $this->db->prepare($sql);
    }

    /**
     * 关闭数据库
     */
    public function del()
    {
        $this->db->close();
    }
}