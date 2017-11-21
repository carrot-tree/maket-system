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
			border-style: groove;
			margin: 0 auto;
			width: 70%;
			height: 510px;
			text-align: center;
		}
	.g_jpg{
		
		width: 70%;
		height: auto;
		margin: 0 auto;

	}
	div img{
		width: 100%;
		height: auto;
		object-fit: cover;
	}
	.title_in{
		margin:0 auto;
		width: 80%;
	}
	.in_in{
		width: 33%
		text-align: center;
	}
</style>
</head>

<body>
	<div class="top">
		<div class="in">
		<h2 class="title">输入商品号和数量和方式</h1>
			<div class="title_in">
				<form class="info" action="#" method="post">
				<div class="in_in">
					<h3>商品号<br></h3><input class="inp" type="text" name="g_no">
				</div>
				<div class="in_in">
					<h3>数量<br></h3><input class="inp" type="text" name="amount">
				</div>
				<div class="in_in">
					<h3>方式<br></h3><input class="inp" type="text" name="style">

				</div>
					<br>
					<input type="submit" name="get" value="get">
					<input type="submit" name="yes" value="确定">
					<input type="submit" name="finishi" value="完成">
				</form>
			</div>
		
		</div>
		
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
			

			//var_dump($g_no,$amount,$style,$admin,$t,$rows["price"],$price1);
			var_dump($price2);
			
		
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
			//var_dump($result_f);
			echo "<h2>本次总计消费".$price_all."元</h2>";
			echo "<h2>欢迎下次光临</h2>";
			
		}

		?>
	</div>
	<div class= g_jpg>
		<img src="goods.jpg"  alt="商品表" />

	</div>






</body>
</html>