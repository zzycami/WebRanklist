<?php
/**
 * Include some function files
 */
if(file_exists("config.php")){
	include_once 'config.php';
}else{
	die("<center><font color='red'>File config.php can't find!</font></center>");
}

$path = "resource/textfiles/";
$errMsg="";
if($_FILES["file"]["error"] > 0){
	die("Error:".$_FILES["file"]["error"]);
}else {
	if($_FILES["file"]["type"]=="text/plain"){
		$file =$path.$_FILES["file"]["name"];
		move_uploaded_file($_FILES["file"]["tmp_name"], $file);
		echo "Saved at ".$file;
	}else {
		$errMsg.="<li>File format is't match!</li>";
	}
}

$url = $_SESSION["TABLE_URL"];
$table = $_SESSION["TABLE_CONTENT"];
$charset = $_SESSION["TABLE_CHARSET"];

$columns = getContent($file);
if (DEBUG){
	echo  "<pre>";
	print_r($columns);
	print_r($table);
	echo "</pre>";
}

$type = $_POST['type'];
if($type == 'column'){
	$index = 0;
	for ($index=0;$index < count($columns);$index++){
		array_push($table[0], $columns[$index][0]);
		for ($i=0;$i<count($table[1]);$i++){
			array_push($table[1][$i], $columns[$index][$i]);
		}
	}
}else if($type == 'row'){
	
}
if (DEBUG){
	echo "<pre>";
	print_r($table);
	echo "</pre>";
}
$_SESSION["TABLE_CONTENT"] = $table;

function redirect($url){
	echo "<script>window.location.href='{$url}'</script>";
}
redirect("/webranklist/setting.php");
?>