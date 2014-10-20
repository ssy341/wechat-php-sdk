<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/10/12 0012
 * Time: 17:46
 */

$db = null;
try {
    $db = new SQLite3('../db/price.sqlite3');
    if (!$db) {
        echo "<h2>Couldn't open the database!</h2>";
        die();
    }
} catch (Exception $e) {
    echo $e;
}

$action = $_GET['action'];

if ('addPrice' == $action) {
    $model = $_POST['model'];
    $cpu = $_POST['cpu'];
    $memory = $_POST['memory'];
    $disk = $_POST['disk'];
    $cdrom = $_POST['cdrom'];
    $display = $_POST['display'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $note = $_POST['note'];
    $brand = $_POST['brand'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $obj = array("model" => $model, "menory" => $memory, "disk" => $disk, "cdrom" => $cdrom, "display" => $display,
        "size" => $size, "price" => $price, "note" => $note, "brand" => $brand, "type" => $type,
        "location" => $location, "date" => $date, "cpu" => $cpu);
    try {
        $result = $db->query("insert into tb_price (model,memory,disk,cdrom,display,size,price,note,brand,type,location,date,cpu)
                              VALUES ('$model','$memory','$disk','$cdrom','$display','$size','$price','$note','$brand','$type','$location','$date','$cpu')");
    } catch (Exception $err) {
        echo $err;
    }
}
if ('addBrand' == $action) {
    $name = $_POST['brand'];
    $result = $db->query("insert into tb_brand (name) VALUES ('$name')");
}
if ('addType' == $action) {
    $name = $_POST['type'];
    $result = $db->query("insert into tb_type (name) VALUES ('$name')");
}
if ('addLocation' == $action) {
    $name = $_POST['location'];
    $result = $db->query("insert into tb_location (name) VALUES ('$name')");
}
/**
 * 获取品牌
 */
if ('fetchBrand' == $action) {
    include('../bean/Brand.php');
    $Brands = array();
    $result = $db->query("select * from tb_brand");
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $obj = new Brand(htmlspecialchars($row['id']), htmlspecialchars($row['name']));
        array_push($Brands, $obj);
    }
    header("Content-type: text/html; charset=utf-8");
    echo json_encode($Brands, JSON_UNESCAPED_UNICODE);
    $db->close();
    exit(0);
}

/**
 * 获取分类
 */
if ('fetchType' == $action) {
    include('../bean/Type.php');
    $Types = array();
    $result = $db->query("select * from tb_type");
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $obj = new Type(htmlspecialchars($row['id']), htmlspecialchars($row['name']));
        array_push($Types, $obj);
    }
    header("Content-type: text/html; charset=utf-8");
    echo json_encode($Types, JSON_UNESCAPED_UNICODE);
    $db->close();
    exit(0);
}

/**
 * 获取地区
 */
if ('fetchLocation' == $action) {
    include('../bean/Location.php');
    $Locations = array();
    $result = $db->query("select * from tb_location");
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $obj = new Location(htmlspecialchars($row['id']), htmlspecialchars($row['name']));
        array_push($Locations, $obj);
    }
    header("Content-type: text/html; charset=utf-8");
    echo json_encode($Locations, JSON_UNESCAPED_UNICODE);
    $db->close();
    exit(0);
}
header("Location: ../addPrice.html");
$db->close();