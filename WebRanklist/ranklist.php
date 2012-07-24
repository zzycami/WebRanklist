<?php
$url = $_POST['url'];
if(!isset($url)){
	$url = "http://acm.tju.edu.cn/tj2010/summary.html";
}
if(file_exists("./library/regex.php"))
	include_once './library/regex.php';
else die("<center><font color='red'>File regex.php can't find!</font></center>");

$content = grapeContent($url);
$table = parseTable($content);
$charset = getEncoding($content);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?> ">
		<title>HDOJ-ACM</title>
		<link href="./style/global.css" media='all' rel="stylesheet" type='text/css' />
	</head>
	<body>
		<table>
			<?php
				echo "<tr id='table_head'>";
				foreach($table[0] as $item){
					echo "<th>";
					echo $item;
					echo "</th>";
				}
				echo "</tr>";
				$i=0;
				foreach($table[1] as $list){
					if(($i++)%2 == 0)
						echo "<tr id='nocolor'>";
					else echo "<tr id='color'>";
					foreach($list as $item){
						echo "<td>";
						echo $item;
						echo "</td>";
					}
					echo "</tr>";
				}
			?>
		</table>
	</body>
</html>