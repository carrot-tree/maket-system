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

//上午销量
$sql0 = "SELECT date(p.time) as 日期上午,SUM(num) as 销量
FROM goods g,purchase p,orders o
WHERE  g.g_no=o.g_no and p.p_no=o.p_no and HOUR(p.time) BETWEEN 7 and 11
GROUP BY date(p.time)";

$result0 = $db->query($sql0);
$rows0 = [];
while($row0 = $result0->fetch_array(MYSQLI_ASSOC))
{
	$rows0[] = $row0;
}
//中午
$sql1 = "SELECT date(p.time) as 日期中午,SUM(num) as 销量
FROM goods g,purchase p,orders o
WHERE  g.g_no=o.g_no and p.p_no=o.p_no and HOUR(p.time) BETWEEN 11 and 15
GROUP BY date(p.time)";
$result1 = $db->query($sql1);
$rows1 = [];
while($row1 = $result1->fetch_array(MYSQLI_ASSOC))
{
	$rows1[] = $row1;
}
//下午
$sql2 = "SELECT date(p.time) as 日期下午,SUM(num) as 销量
FROM goods g,purchase p,orders o
WHERE  g.g_no=o.g_no and p.p_no=o.p_no and HOUR(p.time) BETWEEN 15 and 19
GROUP BY date(p.time)";
$result2 = $db->query($sql2);
$rows2 = [];
while($row2 = $result2->fetch_array(MYSQLI_ASSOC))
{
	$rows2[] = $row2;
}

//每月利润排行
$sql3 = "SELECT month(p.time) as 月份,name as 商品名,SUM(num) 销量,SUM(num)*(g.price-g.cost) as 盈利
from orders o,goods g,purchase p 
WHERE  g.g_no=o.g_no and p.p_no=o.p_no GROUP BY o.g_no,month(p.time)
ORDER BY month(p.time) asc,盈利 desc";
$result3 = $db->query($sql3);
$rows3 = [];
while($row3 = $result3->fetch_array(MYSQLI_ASSOC))
{
	$rows3[] = $row3;
}
//每月最佳销售
$sql4 = "SELECT  best.月份,best.商品名,MAX(best.`销量`) as 最佳销量 FROM 
(
SELECT `name` as 商品名,SUM(num) as 销量 ,month(p.time) as 月份 from orders o,goods g,purchase p 
WHERE  g.g_no=o.g_no and p.p_no=o.p_no 
GROUP BY o.g_no,month(p.time) 
ORDER BY MONTH(p.time),SUM(num) desc
) 
as best
GROUP BY 月份";
$result4 = $db->query($sql4);
$rows4 = [];
while($row4 = $result4->fetch_array(MYSQLI_ASSOC))
{
	$rows4[] = $row4;
}
//最佳电话订购

$sql5 = "SELECT g.`name` AS 商品名,SUM(o.num) AS 销量
FROM goods g,purchase p,orders o
WHERE  g.g_no=o.g_no and p.p_no=o.p_no and p.style = 1
GROUP BY 商品名
ORDER BY 销量 DESC
LIMIT 1
";
$result5 = $db->query($sql5);
$rows5 = [];
while($row5 = $result5->fetch_array(MYSQLI_ASSOC))
{
	$rows5[] = $row5;
}
?>



<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
  <title>月营业情况</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.bootcss.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script>
var _hmt = _hmt || [];
(function() {
var hm = document.createElement("script");
hm.src = "//hm.baidu.com/hm.js?73c27e26f610eb3c9f3feb0c75b03925";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
<style type="text/css">

</style>

</head>

<body>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span4">
			<h2>
				时段销量排行
			</h2>
			<div class="tabbable" id="tabs-982858">
				<ul class="nav nav-tabs">
					<li class="active">
						<a data-toggle="tab" href="#panel-483247">上午销量</a>
					</li>
					<li>
						<a data-toggle="tab" href="#panel-604444">中午销量</a>
					</li>
					<li>
						<a data-toggle="tab" href="#panel-604445">下午销量</a>
					</li>

				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="panel-483247">
						
						<p>
							<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>
										日期
									</th>
									<th>
										销量
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($rows0 as $row0 ) {
								?>
								
								<tr class="success">
									<td>
										<?php echo $row0['日期上午'];?>
									</td>
									<td>
										<?php echo $row0['销量'];?>
									</td>									
								</tr>
								<?php
								}
								?>	
							</tbody>
							</table>
						</p>
					</div>
				
					<div class="tab-pane" id="panel-604444">
						<p>
							<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>
										日期
									</th>
									<th>
										销量
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($rows1 as $row1 ) {
								?>
								
								<tr class="warning">
									<td >
										<?php echo $row1['日期中午'];?>
									</td>
									<td>
										<?php echo $row1['销量'];?>
									</td>									
								</tr>
								<?php
								}
								?>								
							</tbody>
							</table>
						</p>
					</div>
					<div class="tab-pane" id="panel-604445">
						<p>
							<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>
										日期
									</th>
									<th>
										销量
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($rows2 as $row2 ) {
								?>
								
								<tr class="danger">
									<td >
										<?php echo $row2['日期下午'];?>
									</td>
									<td>
										<?php echo $row2['销量'];?>
									</td>									
								</tr>
								<?php
								}
								?>															
							</tbody>
							</table>
						</p>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
				</div>
			
		<div class="span4">
			<h2>
				每月利润排行
			</h2>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>
							月份
						</th>
						<th>
							商品
						</th>
						<th>
							销量
						</th>
						<th>
							盈利
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($rows3 as $row3 ) {
					?>
					<tr class="info">
						<td>
							<?php echo $row3['月份'];?>
						</td>
						<td>
							<?php echo $row3['商品名'];?>
						</td>
						<td>
							<?php echo $row3['销量'];?>
						</td>
						<td>
							<?php echo $row3['盈利'];?>
						</td>
					</tr>
					<?php
					}
					?>	
				</tbody>
			</table>
		</div>
		<div class="span4">
			<h2>
				Best Selling
			</h2>
			<div class="tabbable" id="tabs-233050">
				<ul class="nav nav-tabs">
					<li class="active">
						<a data-toggle="tab" href="#panel-649236">By Phone</a>
					</li>
					<li>
						<a data-toggle="tab" href="#panel-146678">Monthly</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="panel-649236">
						<p>
							<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>
										商品名
									</th>
									<th>
										销量
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($rows5 as $row5 ) {
								?>
								<tr class="success">				
									<td>
										<?php echo $row5['商品名'];?>
									</td>
									<td>
										<?php echo $row5['销量'];?>
									</td>
								</tr>
								<?php
								}
								?>									
							</tbody>
							</table>
						</p>
					</div>
					<div class="tab-pane" id="panel-146678">
						<p>
							<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>
										月份
									</th>
									<th>
										商品名
									</th>
									<th>
										最佳销量
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($rows4 as $row4 ) {
								?>
								<tr class="warning">
									<td>
										<?php echo $row4['月份'];?>
									</td>
									<td>
										<?php echo $row4['商品名'];?>
									</td>
									<td>
										<?php echo $row4['最佳销量'];?>
									</td>
								</tr>
								<?php
								}
								?>									
							</tbody>
							</table>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




</body>
</html>