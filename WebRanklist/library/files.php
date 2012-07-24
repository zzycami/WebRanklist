<?php
/**
 * Read text file
 * @param unknown_type $path
 */
function getContent($path){
	$handle = fopen($path, 'r');
	
	if($handle){
		$data = array();$index = 0;
		while (!feof($handle)){
			$buffer = fgets($handle);
			$buffer = trim($buffer);
			$buffer = iconv("gb2312", "utf-8", $buffer);
			$buffer = preg_replace("/[ ]+/i",',', $buffer);
			$data[$index++] = explode(",", $buffer);
		}
	}else{
		die("<center><font color=\'red\'>Open file {$path} error:{$handle}</font></center>");
	}
	return $data;
}

/**
 * Read *.ini file
 * @param unknown_type $path
 */
function getSetting($path){
	$set = parse_ini_file($path);
	return $set;
	
}

function saveContent($table,$path){
	$handle = fopen($path, "w+");
	if ($handle){
		$str = "";
		foreach ($table[0] as $value){
			$str .= "{$value}  ";
		}
		$str .= "\r\n";
		foreach ($table[1] as $value){
			foreach ($value as $v){
				$str .= "{$v}  ";
			}
			$str .= "\r\n";
		}
		fwrite($handle, $str);
		fclose($handle);
	}
}



class Download {
	var $errorMsg = ""; 			//存储产生的错误信息
	var $fileName = ""; 			//下载文件的名字
	var $mimeType = "text/plain";	//下载文件的mime(Multipurpose Internet Mail Extensions)类型,默认为text/plain
	var $filterType = array();		//存放mime的数组
	var $filter = "";				//存储可上传的文件类型
	
	function __construct($fileName){
		$this->filterType();
		$this->filter();
		$this->fileName = $fileName;
		//$this->downFile();
	}
	
	function downFile(){
		if ($this->checkFileType()){
			$tempfileName = end(explode("/", $this->fileName));
			header("Pragma:public");
			header("Expires:0");
			header("Cache-Component:must-revalidate,post-check=0,pre-check=0");
			header("Cache-Control: private", false);
			header("Content-Length:".filesize($this->fileName));
			header("Content-Disposition:attachment;filename={$tempfileName}");
			header("Content-Transfer-Encoding:binary");
			header("Content-Type:{$this->mimeType}");
			readfile($this->fileName);
			return true; 
		}else {
			return false;
		}
	}
	
	function checkFileType(){
		if(file_exists($this->fileName)){
			$extension = strtolower(end(explode(".", $this->fileName)));
			if(in_array($extension, $this->filter)){
				if (isset($this->filterType[$extension])){
					$this->mimeType = $this->filterType[$extension];
				}
				
				if (empty($this->mimeType)){
					$this->errorMsg .= "<li>Error occured when we try to find!</li>";
					return false;
				}else {
					return true;
				}
			}else {
				$this->errorMsg .= "<li>This type is not supported!</li>";
				return false;
			}
		}else {
			$this->errorMsg .= "<li>File not exist!</li>";
			return false;
		}
	}
	
	function filter(){
		$this->filter = array("txt","xlsx","xls");
	}
	
	function getError(){
		return $this->errorMsg;
	}
	
	//用于填充$this->filterType数组
	function filterType(){
	    $this->filterType['chm']='application/octet-stream';
	    $this->filterType['ppt']='application/vnd.ms-powerpoint';
	    $this->filterType['pptx']='application/vnd.openxmlformats-officedocument.presentationml.presentation';
	    $this->filterType['xls']='application/vnd.ms-excel';
	    $this->filterType['xlsx']='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
	    $this->filterType['doc']='application/msword';
	    $this->filterType['docx']='application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	    $this->filterType['exe']='application/octet-stream';
	    $this->filterType['rar']='application/octet-stream';
	    $this->filterType['js']="javascript/js";
	    $this->filterType['css']="text/css";
	    $this->filterType['hqx']="application/mac-binhex40";
	    $this->filterType['bin']="application/octet-stream";
	    $this->filterType['oda']="application/oda";
	    $this->filterType['pdf']="application/pdf";
	    $this->filterType['ai']="application/postsrcipt";
	    $this->filterType['eps']="application/postsrcipt";
	    $this->filterType['es']="application/postsrcipt";
	    $this->filterType['rtf']="application/rtf";
	    $this->filterType['mif']="application/x-mif";
	    $this->filterType['csh']="application/x-csh";
	    $this->filterType['dvi']="application/x-dvi";
	    $this->filterType['hdf']="application/x-hdf";
	    $this->filterType['nc']="application/x-netcdf";
	    $this->filterType['cdf']="application/x-netcdf";
	    $this->filterType['latex']="application/x-latex";
	    $this->filterType['ts']="application/x-troll-ts";
	    $this->filterType['src']="application/x-wais-source";
	    $this->filterType['zip']="application/zip";
	    $this->filterType['bcpio']="application/x-bcpio";
	    $this->filterType['cpio']="application/x-cpio";
	    $this->filterType['gtar']="application/x-gtar";
	    $this->filterType['shar']="application/x-shar";
	    $this->filterType['sv4cpio']="application/x-sv4cpio";
	    $this->filterType['sv4crc']="application/x-sv4crc";
	    $this->filterType['tar']="application/x-tar";
	    $this->filterType['ustar']="application/x-ustar";
	    $this->filterType['man']="application/x-troff-man";
	    $this->filterType['sh']="application/x-sh";
	    $this->filterType['tcl']="application/x-tcl";
	    $this->filterType['tex']="application/x-tex";
	    $this->filterType['texi']="application/x-texinfo";
	    $this->filterType['texinfo']="application/x-texinfo";
	    $this->filterType['t']="application/x-troff";
	    $this->filterType['tr']="application/x-troff";
	    $this->filterType['roff']="application/x-troff";
	    $this->filterType['shar']="application/x-shar";
	    $this->filterType['me']="application/x-troll-me";
	    $this->filterType['ts']="application/x-troll-ts";
	    $this->filterType['gif']="image/gif";
	    $this->filterType['jpeg']="image/pjpeg";
	    $this->filterType['jpg']="image/pjpeg";
	    $this->filterType['jpe']="image/pjpeg";
	    $this->filterType['ras']="image/x-cmu-raster";
	    $this->filterType['pbm']="image/x-portable-bitmap";
	    $this->filterType['ppm']="image/x-portable-pixmap";
	    $this->filterType['xbm']="image/x-xbitmap";
	    $this->filterType['xwd']="image/x-xwindowdump";
	    $this->filterType['ief']="image/ief";
	    $this->filterType['tif']="image/tiff";
	    $this->filterType['tiff']="image/tiff";
	    $this->filterType['pnm']="image/x-portable-anymap";
	    $this->filterType['pgm']="image/x-portable-graymap";
	    $this->filterType['rgb']="image/x-rgb";
	    $this->filterType['xpm']="image/x-xpixmap";
	    $this->filterType['txt']="text/plain";
	    $this->filterType['c']="text/plain";
	    $this->filterType['cc']="text/plain";
	    $this->filterType['h']="text/plain";
	    $this->filterType['html']="text/html";
	    $this->filterType['htm']="text/html";
	    $this->filterType['htl']="text/html";
	    $this->filterType['rtx']="text/richtext";
	    $this->filterType['etx']="text/x-setext";
	    $this->filterType['tsv']="text/tab-separated-values";
	    $this->filterType['mpeg']="video/mpeg";
	    $this->filterType['mpg']="video/mpeg";
	    $this->filterType['mpe']="video/mpeg";
	    $this->filterType['avi']="video/x-msvideo";
	    $this->filterType['qt']="video/quicktime";
	    $this->filterType['mov']="video/quicktime";
	    $this->filterType['moov']="video/quicktime";
	    $this->filterType['movie']="video/x-sgi-movie";
	    $this->filterType['au']="audio/basic";
	    $this->filterType['snd']="audio/basic";
	    $this->filterType['wav']="audio/x-wav";
	    $this->filterType['aif']="audio/x-aiff";
	    $this->filterType['aiff']="audio/x-aiff";
	    $this->filterType['aifc']="audio/x-aiff";
		$this->filterType['swf']="application/x-shockwave-flash";
	}
}

function saveExcel($table,$title,$path,$type){
	if (file_exists(dirname(__FILE__)."/Classes/PHPExcel.php")){
		require_once dirname(__FILE__).'./Classes/PHPExcel.php';
	}else{
		die("<font color=\"#a90329\">Can'find PHPExcel.php</font>");
	}
	
	$phpExcel = new PHPExcel();
	//设置属性
	$phpExcel->getProperties()	->setCreator("HDOJ")
								->setLastModifiedBy("HDOJ")
								->setTitle("WEB TABLE RANKLIST")
								->setSubject("WEB TABLE RANKLIST")
								->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
								->setKeywords("office 2005 openxml php")
								->setCategory("WEB TABLE RANKLIST");
	//设置活动的sheet
	$phpExcel->setActiveSheetIndex(0);
	//得到活动的sheet
	$activeSheet = $phpExcel->getActiveSheet();
	//设置sheet的标题
	$activeSheet->setTitle("WEB TABLE RANKLIST");
	
	
	//设置单元格内容
	$row = 2;
	$column = "D";
	
	foreach ($table[0] as $value){
		$activeSheet->setCellValue($column.$row,$value);
		$column ++;
	}
	$row = 3;
	foreach ($table[1] as $value){
		$column = "D";
		foreach ($value as $item){
			$activeSheet->setCellValue($column.$row,$item);
			$column ++;
		}
		$row ++;
	}
	
	
	//保存Excel表格
	if(strtolower($type) == "excel2007"){
		$excelWrite = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
	}else if (strtolower($type) == "excel5"){
		$excelWrite = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
	}
	$excelWrite->save($path);
}
