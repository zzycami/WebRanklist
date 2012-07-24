<?php 
/**
 * Include some function files
 */
if(file_exists("config.php")){
	include_once 'config.php';
}else{
	die("<center><font color='red'>File config.php can't find!</font></center>");
}


$method = $_GET["method"];
$type = $_GET['dtype'];

if ($method == 'download' && $type == "txt"){
	$postCode = $_POST["postcode1"];
	if($postCode == $_SESSION['code1']){
		if (isset($_SESSION['title'])){
			$fileName = urlencode($_SESSION['title']);
			$path = "./resource/textfiles/{$fileName}.txt";
		}else {
			$path = "./resource/textfiles/{$postCode}.txt";
		}
		saveContent($_SESSION['TABLE'], $path);
		
		$down = new Download($path);
		$down->downFile();
		if (!$down){
			$errMsg .= $down->getError();
		}
		unlink($path);
	}
}else if ($method == 'download' && $type == "xlsx"){
	$postCode = $_POST["postcode2"];
	if($postCode == $_SESSION['code2']){
		if (isset($_SESSION['title'])){
			$fileName = urlencode($_SESSION['title']);
			$path = "./resource/textfiles/{$fileName}.xlsx";
		}else {
			$path = "./resource/textfiles/{$postCode}.xlsx";
		}
		//saveContent($_SESSION['TABLE'], $path);
		saveExcel($_SESSION['TABLE'], $fileName, $path, "excel2007");
		
		$down = new Download($path);
		$down->downFile();
		if (!$down){
			$errMsg .= $down->getError();
		}
		unlink($path);
	}
}else if ($method == 'download' && $type == "xls"){
	$postCode = $_POST["postcode3"];
	if($postCode == $_SESSION['code3']){
		if (isset($_SESSION['title'])){
			$fileName = urlencode($_SESSION['title']);
			$path = "./resource/textfiles/{$fileName}.xls";
		}else {
			$path = "./resource/textfiles/{$postCode}.xls";
		}
		saveExcel($_SESSION['TABLE'], $fileName, $path, "excel5");
		$down = new Download($path);
		$down->downFile();
		if (!$down){
			$errMsg .= $down->getError();
		}
		unlink($path);
	}
}
