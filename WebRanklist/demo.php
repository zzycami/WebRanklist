<?php  
$s = "
<html>  
<head>  
<title>nested tag test</title>  
<mce:script type=\"text/javascript\"><!--  
alert('fdsafdasfasd');  
// --></mce:script>  
</head>  
<body>  
    <div id=0>  
        <div id=1><img name=\"img1\" id=\"img1\" src=\"\"/>  
            <div id=2><img name=\"img2\" id=\"img2\" src=\"\"/>  
                <div id=3><img name=\"img3\" id=\"img3\" src=\"\"/>  
                </div>  
            </div>  
        </div>  
    </div>  
</body>  
</html>";
$pattern = "/(<\!\w+(?:\s+[^>]*?)+\s*>|<\w+(?:\s+/w+(?:\s*=\s*(?:\"[^\"]*\"|'[^']*'|[^\"'>/s]+))?)*\s*\/?>|<\/\w+\s*>|<\/!--[^-]*-->)/i";  
  
preg_match_all($pattern, $s, $aMatches, PREG_OFFSET_CAPTURE);  
function getMatchTags($s, $arr) {  
          
    $sMatchClose = '';  
    $arrClose = array();  
    $arrReturn = array();  
    for($i=0; $i<count($arr); $i++) {  
          
        $iCount = 0;  
        if (preg_match("/<[^>\s*]*/", $arr[$i][0], $aMatchOpen)) {  
          
            $sMatchClose = '</' . substr($aMatchOpen[0], 1) . '>';      
            for($j=$i; $j<count($arr); $j++) {  
                if (!(stripos($arr[$j][0], $aMatchOpen[0]) === false)) {  
  
                    $iCount ++;  
                    $flag = 1;  
                }  
                if (!(stripos($arr[$j][0], $sMatchClose) === false)) {  
  
                    $iCount --;  
                    $flag = 1;  
                    if($iCount == 0 && $flag == 1) {  
                        $arrClose[] = $arr[$i];  
                        $arrClose[] = $arr[$j];      
                    }  
                }  
            }  
        }  
    }  
    $k=0;  
    for($i=0; $i<count($arrClose); $i+=2) {  
          
        $arrReturn[$k][0] = $arrClose[$i];      
        $arrReturn[$k][1] = $arrClose[$i+1];      
        $arrReturn[$k][2] = substr($s, $arrClose[$i][1], $arrClose[$i+1][1]+strlen($arrClose[$i+1][0])-$arrClose[$i][1]);      
        $k++;  
    }  
    return $arrReturn;  
}  
print_r(getMatchTags($s, $aMatches[0]));  
?>  