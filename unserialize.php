<?php
/**
 * Created by PhpStorm.
 * User: 50412
 * Date: 2017/7/11
 * Time: 22:46
 */
require './mysqlconn.php';
$str = file_get_contents('./conn.txt');
$conn  =unserialize($str);
echo "<pre>";
var_dump($conn);