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

}else
{
	echo "连接成功<br>";
}
//接收数据
$admin = $_POST['admin'];
$password = $_POST['password'];


if ($admin == ''||$password == '')
{
	echo "<script>alert('请输入用户名或密码!'); history.go(-1);</script>";
}else
{
	$sql1 = "SELECT * from admin  where admin = '$admin' && password = '$password'";
	$result = $db->query($sql1);
	$rows = $result->fetch_array(MYSQLI_ASSOC);

	if ($rows) 
	{
		echo "登陆成功，2秒后跳转";
		session_start();
		$_SESSION["admin"] = $admin;
        echo "
          <script>
              setTimeout(function(){window.location.href='home.php';},2000);
          </script>
 
        ";
        

		exit;
    }else
    {

        echo "<script>alert('用户名或密码错误！'); history.go(-1);</script>";
 
    }
} 

?>