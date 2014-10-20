<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/10/12 0012
 * Time: 18:13
 * 产品价格类
 */

class Price {
    public $model;
    public $memory;
    public $disk;
    public $cdrom;
    public $cpu;
    public $display;
    public $size;
    public $price;
    public $note;
    public $brand;
    public $type;
    public $location;
    public $date;

    function __construct($obj)
    {
        $this->brand = $obj['brand'];
        $this->cdrom = $obj['cdrom'];
        $this->date = $obj['date'];
        $this->disk = $obj['disk'];
        $this->display = $obj['display'];
        $this->location = $obj['location'];
        $this->memory = $obj['memory'];
        $this->model = $obj['model'];
        $this->note = $obj['note'];
        $this->price = $obj['price'];
        $this->size = $obj['size'];
        $this->type = $obj['type'];
        $this->cpu = $obj['cpu'];
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function getCdrom()
    {
        return $this->cdrom;
    }

    /**
     * @param mixed $cdrom
     */
    public function setCdrom($cdrom)
    {
        $this->cdrom = $cdrom;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * @param mixed $disk
     */
    public function setDisk($disk)
    {
        $this->disk = $disk;
    }

    /**
     * @return mixed
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @param mixed $display
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @param mixed $menory
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * @param mixed $cpu
     */
    public function setCpu($cpu)
    {
        $this->cpu = $cpu;
    }

} 