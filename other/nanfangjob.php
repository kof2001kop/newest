<?php
require('RSS-master/vendor/CyrilPerrin/Rss/Rss.php');
header('content-type:application/rss+xml');
//header('content-type:text/html; charset=utf-8');//charset=utf-8

$ret = file_get_contents('http://job168.com/jobfair/index.jsp');
$ret= iconv('gb2312','utf-8',$ret);
$ret=substr($ret,strpos($ret,"zphList"));
//echo $ret;
for ($i=0; $i<10;$i++)
{
$ret=substr($ret,strpos($ret,'<li>')+4);
$beg=strpos($ret,'>')+1;
$times[$i]=substr($ret,$beg,strpos($ret,'</span>')-6-$beg);

$ret=substr($ret,strpos($ret,'meeting_no=')+11);
$beg=0;
$link[$i]=substr($ret,$beg,strpos($ret,'"')-$beg);
    
$ret=substr($ret,strpos($ret,'blank">')+7);
$titles[$i]=substr($ret, 0, strpos($ret,'</a>'));
      
}


$rss=new CyrilPerrin\Rss\Rss(0, '0','0','zh',120,0);
for($i=0;$i<10;$i++)
{
   
$rss->addItem($i,'『'.$times[$i].'』 '.$titles[$i],'http://1.jiese.applinzi.com/nanjobcom.php?key='.$link[$i], '', 'Job',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', 'https://www.job.com');
}
echo $rss;
?>
