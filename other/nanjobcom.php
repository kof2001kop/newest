<?php
header('content-type:text/html; charset=gb2312');//charset=utf-8
$ret = file_get_contents("http://www.job168.com/jobfair/zphInfo.jsp?meeting_no=".$_GET['key']);

$ret=substr($ret,strpos($ret,"zphComsList"));
$ret=substr($ret,strpos($ret,'</tr>')+5);
$loopSize = substr_count($ret, '</tr>') - 1;

echo '<p>';
$sl=iconv('utf-8','gb2312', '『');//.$PlaceId[$i]   '</br></br>';
$sr=iconv('utf-8','gb2312', '』');
for($i=0; $i< $loopSize; $i++)
{
    $ret=substr($ret,strpos($ret,'comPlaceId">')+12);
    $PlaceId[$i] = substr($ret, 0, strpos($ret,'</td>'));
    
    $ret=substr($ret,strpos($ret,'zphComsName">')+13);   
    $name[$i] = substr($ret, 0, strpos($ret,'</span>')); 
    echo   $name[$i].$sl.$PlaceId[$i].$sr.'</br></br>';
}

echo '</p>';
?>
