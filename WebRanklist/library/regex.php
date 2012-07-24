<?php
include_once('simple_html_dom.php');

function arrayHtmlSpecialChar(&$in){
	if(is_array($in)){
		foreach ($in as &$value){
			arrayHtmlSpecialChar($value);
		}
	}else if(is_string($in)){
		$in = htmlspecialchars($in);
	}
}

/**
 * used to parse table,to get the content of the table
 * @param $table
 * $table is a string that with <table>...</table>
 */

function parseTable($table){
	$regex = array(
	'table'=>'/<table(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/table>/i',
	'tr'=>'/<tr(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/tr>/i',
	'th'=>'/<th(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/th>/i',
	'td'=>'/<td(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/td>/i',
	'thead'=>'/<thead(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/thead>/i',
	'tbody'=>'/<tbody(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/tbody>/i',
	'rid'=>'/<([a-z][^>]*)>(.*)<\/\\1>/i',
	'htmltag'=>'/<\/?[a-zA-Z][^>]*>/i',
	'meta'=>'/<meta(\s+http-equiv\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))?(\s+content\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))(\s+name\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))?(\s+scheme\s*=\s*(("[^"]*")|(\'[^\']\')|([^ ]*)))?\/?>/i',
	'shortmeta'=>'/<meta.*?charset\s*=\s*(\w+).*?\/?>/i'
	);
	

	$table = str_replace("\n", "", $table);
	
	$title = array();
	if(preg_match_all($regex['thead'], $table, $matches,PREG_SET_ORDER)){
		$thead = $matches[0][8];
		if(!preg_match_all($regex['th'], $thead, $matches)){
			preg_match_all($regex['td'], $thead, $matches);
		}
		$head = $matches[8];
	}else {
		preg_match_all($regex['tr'], $table, $result,PREG_SET_ORDER);
		if(!preg_match_all($regex['th'], $result[0][8], $matches)){
			preg_match_all($regex['td'], $result[0][8], $matches);
		}
		$head = $matches[8];
	}

	foreach ($head as &$value){
		$value = strip_tags($value);
		$value = str_replace("&nbsp;", "", $value);
	}
	$ret[0] = $head;

	if (preg_match_all($regex['tbody'], $table, $matches, PREG_SET_ORDER)){
		$tbody = $matches[0][8];
		preg_match_all($regex['tr'], $tbody, $matches, PREG_SET_ORDER);
		
		$data = array();$index = 0;
		for ($index =0;$index<count($matches);$index++){
			preg_match_all($regex['td'], $matches[$index][8], $temp);
			foreach ($temp[8] as &$value){
				$value = strip_tags($value);
				$value = str_replace("&nbsp;", "", $value);
			}
			$data[$index] = $temp[8];
		}
	}else {
		preg_match_all($regex['tr'], $table, $matches, PREG_SET_ORDER);
		$data = array();$index = 0;
		for ($index = 1;$index<count($matches);$index++){
			preg_match_all($regex['td'], $matches[$index][8], $temp);
			foreach ($temp[8] as &$value){
				$value = strip_tags($value);
				$value = str_replace("&nbsp;", "", $value);
			}
			$data[$index-1] = $temp[8];
		}
	}
	
	
	
	$ret[1] = $data;
	return $ret;
}




function getTable($content, $mode = 'all'){
	$regex = array(
	'table'=>'/<table(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/table>/i',
	'tableX'=>'/<table.*?>(.*)<\/table>/i',
	'javascript'=>'/<script.*?>.*?<\/script>/i',
	'simpletable'=>'/<table.*?>(.*?)<\/table>/i',
	'tr'=>'/<tr(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/tr>/i',
	'th'=>'/<th(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/th>/i',
	'td'=>'/<td(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/td/i',
	'thead'=>'/<thead(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/thead>/i',
	'tbody'=>'/<tbody(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/tbody>/i',
	'rid'=>'/<([a-z][^>]*)>(.*)<\/\\1>/i',
	'htmltag'=>'/<\/?[a-zA-Z][^>]*>/i',
	'meta'=>'/<meta(\s+http-equiv\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))?(\s+content\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))(\s+name\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))?(\s+scheme\s*=\s*(("[^"]*")|(\'[^\']\')|([^ ]*)))?\/?>/i',
	'shortmeta'=>'/<meta.*?charset\s*=\s*(\w+).*?\/?>/i'
	);
	
	$content = str_replace("\n", "", $content);
	$content = preg_replace($regex['javascript'], "", $content);
	preg_match_all($regex['table'], $content, $result, PREG_SET_ORDER);
	$tableContent = array();$tablesAll = array();$index = 0;
	foreach ($result as $value){
		$tablesAll[$index] = $value[0];
		$tableContent[$index] = $value[8];
		$index ++;
	}
	
	if (strtolower($mode) == "all"){
		return $tablesAll;
	}else if(strtolower($mode) == "content"){
		return $tableContent;
		
	}
}

function getTableX($content, $mode = 'all'){
	$regex = array(
	'table'=>'/<table(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/table>/i',
	'tablex'=>'/<table.*?>(.*)<\/table>/i',
	'tables'=>'/<table.*?>(^(<table.*?>)*?)<\/table>/i',
	'javascript'=>'/<script.*?>.*?<\/script>/i',
	'simpletable'=>'/<table.*?>(.*?)<\/table>/i',
	'tr'=>'/<tr(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/tr>/i',
	'th'=>'/<th(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/th>/i',
	'td'=>'/<td(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/td/i',
	'thead'=>'/<thead(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/thead>/i',
	'tbody'=>'/<tbody(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/tbody>/i',
	'rid'=>'/<([a-z][^>]*)>(.*)<\/\\1>/i',
	'htmltag'=>'/<\/?[a-zA-Z][^>]*>/i',
	'meta'=>'/<meta(\s+http-equiv\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))?(\s+content\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))(\s+name\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))?(\s+scheme\s*=\s*(("[^"]*")|(\'[^\']\')|([^ ]*)))?\/?>/i',
	'shortmeta'=>'/<meta.*?charset\s*=\s*(\w+).*?\/?>/i'
	);
	
	$content = str_replace("\n", "", $content);
	$content = preg_replace($regex['javascript'], "", $content);
	
	$tables = array();
	$stack = array();
	preg_match_all($regex['tablex'], $content, $result, PREG_SET_ORDER);
	foreach ($result as $value){
		array_push($stack, $value[1]);
		array_push($tables, $value[1]);
		
		/*echo "<br>";
		print_r($value);
		echo "<br/>";*/
	}
	while (count($stack) != 0){
		$item = array_pop($stack);
		preg_match_all($regex['tables'], $item, $result, PREG_SET_ORDER);
		echo "<br>";
		print_r($result);
		echo "<br/>";
		foreach ($result as $value){
			/*echo "<br>";
			print_r($value);
			echo "<br/>";*/
			array_push($stack, $value[1]);
			array_push($tables, $value[1]);
		}
	}
	return $tables;
}


function getTableS($content){
	$content = str_get_html($content);
	$table = $content->find("table");
	echo "<pre>";
	print_r($table);
	echo "</pre>";
}




function getEncoding($content){
	$regex = array(
	'table'=>'/<table(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/table>/i',
	'tr'=>'/<tr(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/tr>/i',
	'th'=>'/<th(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/th>/i',
	'td'=>'/<td(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/td>/i',
	'thead'=>'/<thead(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/thead>/i',
	'tbody'=>'/<tbody(\s+\w+((\s*=\s*)(("[^"]*")|(\'[^\']*\')|([^ ]*)))?)*\s*>(.*?)<\/tbody>/i',
	'rid'=>'/<([a-z][^>]*)>(.*)<\/\\1>/i',
	'htmltag'=>'/<\/?[a-zA-Z][^>]*>/i',
	'meta'=>'/<meta(\s+http-equiv\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))?(\s+content\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))(\s+name\s*=\s*(("[^"]*")|(\'[^\']*\')|([^ ]*)))?(\s+scheme\s*=\s*(("[^"]*")|(\'[^\']\')|([^ ]*)))?\s*\/?>/i',
	'shortmeta'=>'/<meta.*?charset\s*=\s*(\w+).*?\/?>/i',
	'charset'=>'/\s*charset\s*=\s*([\w-]+)/i',
	'charsets'=>'/<meta.*?((ASCII)|(GB2312)|(GBK)|(GB18030)|(unicode)|(UTF-8)|(UTF-16)|(BIG5)|(IOS-8859-1)|(UCS-2)).*?>/i',
	'quot'=>'/[\'"]/i'
	);
	
	$content = str_replace("\n", "", $content);
	
	preg_match_all($regex['meta'], $content, $result,PREG_SET_ORDER);
	
	$httpEquiv = "";
	$charset = "";
	foreach ($result as $value){
		if (isset($value[1])){
			if($value[2]!=""){
				$httpEquiv = $value[2];
			}
		}
		$httpEquiv = preg_replace($regex['quot'], "", $httpEquiv);
		$httpEquiv = trim($httpEquiv);
		if (strtolower($httpEquiv) == 'content-type'){
			$charset = preg_replace($regex['quot'], "", $value[7]);
			$charset = trim($charset);
			preg_match($regex['charset'], $charset, $matches);
			$charset = $matches[1];
		}else if(strtolower($httpEquiv) == 'charset'){
			$charset = preg_replace($regex['quot'], "", $value[7]);
			$charset = trim($charset);
		}
		if($charset != ""){
			return strtolower($charset);
		}
	}
	if($charset == ""){
		preg_match_all($regex['charsets'], $content, $matches,PREG_SET_ORDER);
		$charset = $matches[1];
		$charset = strtolower($charset);
		if($charset=='ascii'||$charset=='gb2312'||$charset=='gbk'||
		$charset=='gb18030'||$charset=='unicode'||$charset=='utf-8'||
		$charset=='utf-16'||$charset=='big5'||$charset=='ios-8859-1'||
		$charset=='ucs-2'){
			return strtolower($charset);
		}else{
			$charset = "failed";
			return strtolower($charset);
		}
	}
	
}



/**
 * change the format of the array that stored table
 * @param unknown_type $table
 */
function formatTable($table){
	if(is_array($table)){
		$ret = array();
		for ($i=0;$i<(count($table[0]));$i++){
			$temp = array();$index=0;
			foreach ($table[1] as $item){
				if(isset($item[$i]))
					$temp[$index++] = $item[$i];
			}
			$ret[$table[0][$i]] = $temp;
		}
		return $ret;
	}else{
		return false;
	}
}

/**
 * Get html content by url
 * Enter description here ...
 * @param unknown_type $url
 */
function grapeContent($url){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$result = curl_exec($ch);
	$charset = getEncoding($result);
	if ($charset == "failed"){
		$charset = "utf-8";
	}
	$result = iconv($charset, "utf-8//IGNORE", $result);
	curl_close($ch);
	return $result;
	
}