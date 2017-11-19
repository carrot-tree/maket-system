
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
?>




<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
  <title>收银</title>
  <meta charset="utf-8">

<style type="text/css">
	.top{
			margin: 0 auto;
			width: 60%;
			height: 200px;
			text-align: center;
		}
</style>
</head>

<body>
	<div class="top">
	<h2 class="title">输入商品号和数量和方式</h1>
	<form class="info" action="#" method="post">
		<input class="inp" type="text" name="g_no">
		<input class="inp" type="text" name="amount">
		<input class="inp" type="text" name="style">
		<input type="submit" name="yes" value="确定">
	</form>
	<form class="info" action="#" method="post">
		
		<input type="submit" name="get" value="get">
	</form>
	<form class="info" action="#" method="post">
		
		<input type="submit" name="finishi" value="完成">
	</form>
	
	<?php

	if (isset($_POST['get'])) 
	{
		session_start();
		$price_all = 0;
		$_SESSION["price_all"] = $price_all;
		$admin = $_SESSION["admin"];
		$sql4 = "INSERT INTO purchase (time,cashier) values (now(),'$admin')";
		$result4 = $db->query($sql4);
		var_dump($result4);
	}
	if (isset($_POST['yes'])) 
	{

		//往purchase表插入一条数据
		$g_no = (int)$_POST['g_no'];
		$amount = (int)$_POST['amount'];
		$style = $_POST['style'];
		session_start();
		$admin = $_SESSION["admin"];
		$t=date("Y-m-d H:i:s");
		$sql = "SELECT price FROM goods where g_no=$g_no";
		$result = $db->query($sql);
		$rows = $result->fetch_array(MYSQLI_ASSOC);
		$price1 = (float)$rows["price"];
		$price2 = $price1 * $amount;
		
		$price_all = $_SESSION["price_all"];
		$price_all += $price2;
		

		var_dump($g_no,$amount,$style,$admin,$t,$rows["price"],$price1);
		var_dump($price2);
		//$sql0 = "INSERT INTO purchase (style, cashier, price, time) values ( '$style', '$admin', '$price2', now())";
		//$result0 = $db->query($sql0);
	
		$sql2 = "SELECT p_no FROM purchase ORDER BY p_no desc";
		$result2 = $db->query($sql2);
		$rows2 = $result2->fetch_array(MYSQLI_ASSOC);
		$p_no = (int)$rows2["p_no"];
		//往orders表插入一条数据
		$sql3 = "INSERT INTO orders (g_no, p_no, num) values ('$g_no', $p_no ,'$amount')";
		$db->query($sql3);
		
		$_SESSION["price_all"] = $price_all;
		$_SESSION["style"] = $style;
		$_SESSION["p_no"] = $p_no;

	}
	if (isset($_POST['finishi'])) 
	{
		session_start();
		$price_all = $_SESSION["price_all"];
		$style = $_SESSION["style"];
		$p_no = $_SESSION["p_no"];
		$sql_f = "UPDATE `maket`.`purchase` set style='$style', price='$price_all' where p_no='$p_no' ";
		$result_f = $db->query($sql_f);
		var_dump($price_all,$style,$p_no);
		var_dump($result_f);
		
	}

	?>
	</div>






</body>
</html>