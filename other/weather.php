<?php
header('content-type:text/html; charset=utf-8');//charset=utf-8

$ret = file_get_contents('http://rss.weibodangan.com/weibo/rss/2294193132');

//file错误
if (!$ret)
{
    echo '<p>地址错误~</p>';
    return;
}

$ret = str_replace("<![CDATA[","", $ret); 
$ret = str_replace("]>","", $ret); 
$ret = str_replace("]<","<", $ret); 
$ret = preg_replace( '/\[(.*)\]/', '',$ret);
$ret = preg_replace( '/\#(.*)\#/', '',$ret);
$ret = preg_replace( '/\@(.*)\@/', '',$ret);
$ret = preg_replace( '/[a-z]+:\/\/[a-z0-9_\-\/.%]+/i', '',$ret);
$ret = str_replace("【","『", $ret);  
$ret = str_replace("】","』", $ret);  

preg_match_all("@<title[^>]*>(.*?)<\/title>@si",$ret, $regs);
preg_match_all("@<pubDate[^>]*>(.*?)<\/pubDate>@si",$ret, $times);

$arrSize = count($regs[1]);


	//打开
	use sinacloud\sae\Storage as Storage;
	$s = new Storage();
	$divSize = $s->getObject("kpr", "div.txt")->{"body"};

	if ($divSize == 3)
	{
        $divi = '─────────────';
    }
	else if ($divSize == 1)
	{
        $divi = '￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣';
	}
	else
    {
         $divi = '￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣';
    }


echo "<p>";
for ($i = 1; $i < $arrSize; $i++)
{
    if (empty($regs[1][$i]))
    {
        continue;
    }
  	
     $temp = explode(" ", $times[1][$i - 1]);
    // var_dump($temp);
     $sizeT = count($temp);
    
    if (!empty($temp[$sizeT - 2]))
    {
        $timeEn = '('.$temp[$sizeT - 2].')';
    }
    else
        $timeEn = $temp[$sizeT - 2];
    
    echo $regs[1][$i]." ".$timeEn."<br><br>";
  
    echo "$divi<br>";
}
echo "</p>";

?>
