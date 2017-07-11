<?php
/**
 * Created by PhpStorm.
 * User: 50412
 * Date: 2017/7/11
 * Time: 22:36
 */
require './mysqlconn.php';
$arr = array(
    'host'=>'localhost',
    'port'=>3306,
    'user'=>'root',
    'pass'=>'123456',
    'charset'=>'utf8',
    'dbname'=>'demo'
);
$conn = MySQLDB::GetDB($arr);
$str = serialize($conn);
file_put_contents('./conn.txt',$str);