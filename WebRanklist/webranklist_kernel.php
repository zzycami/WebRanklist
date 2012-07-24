<?php 
/**
 * Include some function files
 */
if(file_exists("config.php")){
	include_once 'config.php';
}else{
	die("<center><font color='red'>File config.php can't find!</font></center>");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WEB RANKLIST KERNEL</title>
<style type="text/css">
@font-face {
   font-family: 'Cantarell';
   src: url(./style/fonts/Cantarell-Regular.eot);
   src: local('Cantarell'), url('./style/fonts/Cantarell-Regular.ttf') format('truetype');
}

body {
	background-color: #000;
	color:#a90329;
	font-family:Cantarell, "微软雅黑", sans-serif;
	font-size:12px;
	font-weight:bold;
}
</style>
</head>
<body>
<?php 
$url = "http://localhost/tabledemox.html";
//$url = "http://acm.hdu.edu.cn/vcontest/vtl/vtllist/alllist";
//$url = "http://acm.tju.edu.cn/tj2010/summary.html";
//$url = "http://acm.zju.edu.cn/onlinejudge/showRankList.do?contestId=1&from=0&order=AC";
//$url = "http://acm.hdu.edu.cn/ranklist.php";
$content = grapeContent($url);
$tables = getTableS($content);
/*arrayHtmlSpecialChar($tables);
echo "<br>";
print_r($tables);
echo "<br/>";*/
?>
</body>
</html>