<?php
header("Content-type: text/html; charset=utf-8");
//连接数据库
$host = '127.0.0.1';
$user = 'root';
$pwd = '';
$dbname = 'maket';

$db = new mysqli($host,$user,$pwd,$dbname);
$db->query("SET NAMES UTF8");

if ($db->connect_errno<> 0)
{
	die("连接失败<br>");

}



$sql = "SELECT price FROM goods where g_no=$g_no";
$result = $db->query($sql);
$rows = $result->fetch_array(MYSQLI_ASSOC);

var_dump($rows);
?>
