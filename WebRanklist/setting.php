<?php
//http://localhost/tabledemox.html
/**
 * Include some function files
 */
if(file_exists("config.php")){
	include_once 'config.php';
}else{
	die("<center><font color='red'>File config.php can't find!</font></center>");
}

$errMsg = "";

$index = isset($_GET['index'])?$_GET['index']:1;
if (isset($index)){
	$tables = $_SESSION['tables'];
	$table = parseTable($tables[$index-1]);
	$_SESSION['TABLE'] = $table;
	$_SESSION['current_table'] = $index;
}

$method = $_GET["method"];
if ($method == "url"){
	$url = $_POST['url'];
	if (isset($_SESSION["TABLE"])){
		unset($_SESSION["TABLE"]);
	}
	if (isset($_SESSION["URL"])){
		unset($_SESSION["URL"]);
	}
	if (isset($_SESSION["CHARSET"])){
		unset($_SESSION["CHARSET"]);
	}
	if (isset($_SESSION['table_num'])){
		unset($_SESSION['table_num']);
	}
	if (isset($_SESSION['current_table'])){
		unset($_SESSION['current_table']);
	}
	if (isset($_SESSION['tables'])){
		unset($_SESSION['tables']);
	}
	$_SESSION["url"]=$url;
}else if ($method == "addinfo"){
	$url = $_POST["url"];
	if (isset($_SESSION["TABLE"])){
		$table = $_SESSION["TABLE"];
	}
	$path = "resource/textfiles/";
	$errMsg="";
	if($_FILES["file"]["error"] > 0){
		die("Error:".$_FILES["file"]["error"]);
	}else {
		if($_FILES["file"]["type"]=="text/plain"){
			$file =$path.$_FILES["file"]["name"];
			move_uploaded_file($_FILES["file"]["tmp_name"], $file);
		}else {
			$errMsg.="<li>File format is't match!</li>";
		}
	}
	
	
	
	$type = $_POST['type'];
	if($type == 'column'){
		$columns = getContent($file);
		$index = 0;
		for ($index=0;$index < count($columns);$index++){
			array_push($table[0], $columns[$index][0]);
			for ($i=0;$i<count($table[1]);$i++){
				array_push($table[1][$i], $columns[$index][$i]);
			}
		}
	}else if($type == 'row'){
		$row = getContent($file);
		echo "encoding:".mb_detect_encoding($row[0][1]);
		foreach ($row as $value){
			array_push($table[1], $value);
		}
	}
	
}else if ($method == 'save'){
	$postCode = $_POST["postcode"];
	if($postCode == $_SESSION['code']){
		if(isset($_SESSION['title'])){
			unset($_SESSION['title']);
		}
		$title = $_POST['title'];
		$_SESSION['title'] = $title;
		
		$url = $_SESSION['url'];
		$encoding = $_SESSION['CHARSET'];
	
		$dbTable = new DbTableTables($set);
		$tableNum = $dbTable->tableInsert($url, $encoding, $title);
		
		$table = $_SESSION['TABLE'];
		$xml = new XmlFactory();
		$xml->xmlWrite($table,$tableNum);
	}
}


if (!isset($_SESSION["TABLE"])){
	$content = grapeContent($url);
	$tables = getTable($content);
	$table = parseTable($tables[0]);
	$charset = getEncoding($content);
	$_SESSION['tables'] = $tables;
	$_SESSION['table_num'] = count($tables);
	$_SESSION['current_table'] = 1;
	$_SESSION["TABLE"]=$table;
	$_SESSION["CHARSET"] = $charset;
}

if(!isset($charset)){
	$charset = $_SESSION["CHARSET"];
}
if (!$table){
	$table = $_SESSION["TABLE"];
}






?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>TABLE CONFIG-WEB RANKLIST</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="./style/setting.css" media='all' rel="stylesheet" type='text/css' />
	</head>
<body>
	<div id="control_panel">
		<h1 class="title">TABLE CONFIG</h1>
		<div class="form">
			<form action="setting.php?method=url" method="post">
				<input style="width:400px;" class="url" name="url" type="text" 
				value="<?php if(isset($_SESSION['url'])) echo $_SESSION['url'];else echo "Please Input a url"; ?>" 
				onfocus="if(this.value!=''){ this.value='';}" 
				onblur="if(this.value==''){ this.value='<?php if(isset($_SESSION['url'])) echo $_SESSION['url'];else echo "Please Input a url"; ?>';}" />
				<input class="button" type="submit" value="Go" />
			</form>
		</div>
		
		<fieldset>
			<legend>Current Page: <span><?php if(isset($_SESSION['url'])){echo $_SESSION['url'];}?></span></legend>
			<?php 
			if ($errMsg != ""){
				echo "<div class='errMsg'><ul>";
				echo $errMsg;
				echo "</ul></div>";
			}
			?>
			<table id="table_info">
				<tr><td class="item">TABLE NUMBERS:</td><td><?php echo $_SESSION['table_num'];?></td></tr>
				<tr><td class="item">CURRENT TABLE INDEX:</td><td>
				<?php 
					echo "<ul class='tablelist'>";
					for ($i=1;$i<=$_SESSION['table_num'];$i++){
						if ($i == $_SESSION['current_table']){
							echo "<li><a style=\"color:#a90329;font-size:20px;\" href='setting.php?index={$i}'>[{$i}]</a></li>";
						}else {
							echo "<li><a href='setting.php?index={$i}'>[{$i}]</a></li>";
						}
					}
					echo "</ul>";
				?>
				</td></tr>
				<tr><td class="item">TABLE HEAD COUNT:</td><td><?php echo count($table[0]);?></td></tr>
				<tr><td class="item">TABLE ROWS COUNT:</td><td><?php echo count($table[1]);?></td></tr>
				<tr><td class="item">TABLE ENCODING CHARSET:</td><td><?php echo $charset;?></td></tr>
				<tr><td class="item">ADD COLUMNS:</td><td>
				<div class="form">
				<form action="setting.php?method=addinfo" enctype="multipart/form-data" method="post">
					<input type="hidden" name="type" value="column" />
					<input type="hidden" name="url" value="<?php echo $_SESSION['url']; ?>" />
					<input type="file" name="file" class="url" />
					<input type="submit" class="button" value="Go" />
				</form></div></td></tr>
				
				<tr><td class="item">ADD ROWS:</td><td>
				<div class="form">
				<form action="setting.php?method=addinfo" enctype="multipart/form-data" method="post">
					<input type="hidden" name="type" value="row" />
					<input type="hidden" name="url" value="<?php echo $url; ?>" />
					<input type="file" name="file" class="url" />
					<input type="submit" class="button" value="Go" />
				</form></div></td></tr>
				
				
				<tr><td class="item">TABLE TITLE:</td><td>
				<div class="form">
					<form method="post" action="setting.php?method=save">
						<?php $_SESSION['code'] = mt_rand(1, 1000);?>
						<input type="hidden" name="postcode" value="<?php echo $_SESSION['code']; ?>" />
						<input style="width:400px;" class="url" type="text" name="title" 
						value="<?php if(isset($_SESSION['title'])){echo $_SESSION['title'];}else {echo "INPUT A TITLE FOR THE TABLE AND WE WILL SAVE IT";}?>" onfocus="if(this.value != ''){this.value ='';}"  
						onblur="if(this.value == ''){this.value = '<?php if(isset($_SESSION['title'])){echo $_SESSION['title'];}else {echo "INPUT A TITLE FOR THE TABLE AND WE WILL SAVE IT";}?>';}"/>
						<input type="submit" class="button" value="Go" />
					</form>
				</div>
				</td></tr>
				
				
				<tr><td class="item">TABLE DOWNLOAD:</td>
				<td>
				<table class = "innertable">
				<tr>
				<td>
				<form method="post" action="download.php?method=download&dtype=txt">
					<?php $_SESSION['code1'] = mt_rand(0, 1000);?>
					<input type="hidden" name="postcode1" value="<?php echo $_SESSION['code1']; ?>"/>
					<input class="dbutton" type="submit" value="DOWNLOAD"/>
				</form>
				</td>
				<td><label>Download by txt Document</label></td>
				<td>
				<form method="post" action="download.php?method=download&dtype=xlsx">
					<?php $_SESSION['code2'] = mt_rand(0, 1000);?>
					<input type="hidden" name="postcode2" value="<?php echo $_SESSION['code2']; ?>"/>
					<input class="dbutton" type="submit" value="DOWNLOAD"/>
				</form>
				</td>
				<td><label>Download by Excel 2007 Document</label></td>
				<td>
				<form method="post" action="download.php?method=download&dtype=xls">
					<?php $_SESSION['code3'] = mt_rand(0, 1000);?>
					<input type="hidden" name="postcode3" value="<?php echo $_SESSION['code3']; ?>"/>
					<input class="dbutton" type="submit" value="DOWNLOAD"/>
				</form>
				</td>
				<td><label>Download by Excel 2003 Document</label></td>
				</tr>
				</table>
				</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset>
		<legend>Table Preview</legend>
		<div id="table">
		<table id="pretable">
			<thead>
			<?php 
				echo "<tr id='table_head'>";
				foreach($table[0] as $item){
					echo "<th>";
					echo $item;
					echo "</th>";
				}
				echo "</tr>";
			?>
			</thead>
			<tbody>
			<?php 
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
			</tbody>
		</table>
	</div>
	</fieldset>
	
	</div>
	
</body>
</html>