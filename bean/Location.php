<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/10/12 0012
 * Time: 18:14
 * 报价地区
 */

class Location {
    public $id;
    public $name;
    public $note;

    function __construct($id, $name, $note=null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }




} 