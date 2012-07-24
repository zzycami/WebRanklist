<html>
	<head>
		<title>HDOJ-ACM</title>
		<link href="./style/global.css" rel="stylesheet" type="text/css" media="all" />
	</head>
	<body>
		<div>
			<ul>
				<li><a href="addinfo.php">Add Info</a></li>
				<li><a href="setting.php">Setting</a></li>
			</ul>
		</div>
		<div id="content">
			<div class="form">
				<form action="ranklist.php" method = "post">
				<input type="text" name = "url" value = "http://acm.tju.edu.cn/tj2010/summary.html" style="width:500px;" 
				onfocus="if(this.value != ''){this.value='';}" onblur="if(this.value == ''){ this.value = 'Please Input a url';}"
				/>
				<input class="button" type="submit" value = "Go"/>
				</form>
			</div>
		</div>
	</body>
</html>