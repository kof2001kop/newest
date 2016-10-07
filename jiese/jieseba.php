<?php

// Require
require('../vendor/CyrilPerrin/Rss/Rss.php');

// HTTP header
header('content-type:application/rss+xml; charset=utf-8');


//次数限制30次
	use sinacloud\sae\Storage as Storage;
	$s = new Storage();
	$openCon = explode("@%", $s->getObject("kpr", "jieseLimit.txt")->{"body"});
	
	if ($openCon[0] == date('m/d', time()))
    {
        if ($openCon[1] <= 0)
        {
            $rss = new CyrilPerrin\Rss\Rss('163 News', 'http://news.163.com/special/0001386F/rank_news.html', '跟帖排行榜', 'zh', '120', $_SERVER['REQUEST_TIME']);
			$rss->addItem(0, '今日配额用完', 'http://1.jiese.applinzi.com/rest.php', '', 'Rank News','Cyril', $_SERVER['REQUEST_TIME'], 'Comments', $linkModel[$i]);
            echo $rss;
            return;
        }
        else
        {
            $openCon[1]--;
            $updateT = date('m/d', time());	
			$updateT .= "@%";
            $updateT .= $openCon[1];
			$s->putObject($updateT, "kpr", "jieseLimit.txt", array(), array('Content-Type' => 'text/txt'));     
        }
    }
	else
    {	
        $updateT = date('m/d', time());	
		$updateT .= "@%5";

		$s->putObject($updateT, "kpr", "jieseLimit.txt", array(), array('Content-Type' => 'text/txt'));
    }




$ret = file_get_contents('http://tieba.baidu.com/mo/q----,sz@320_240-1-3---1/m?kw=%E6%88%92%E8%89%B2&lp=5011&lm=&pn=40');

//file错误
if (!$ret)
{
	$rss = new CyrilPerrin\Rss\Rss(
    $_GET['key'], 'https://www.zhihu.com', '知乎', 'zh', '120', $_SERVER['REQUEST_TIME']);
 
	$rss->addItem(
        1, '地址错误', 'http://1.jiese.applinzi.com/rest.php', '', 'Zhihu',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', 'https://www.zhihu.com');
  	
    echo $rss;
    
    return;    
}

$ret = substr($ret, strpos($ret,'</div>'));
$ret = substr($ret, strpos($ret,';is_bakan')+ 8);
$ret = substr($ret, strpos($ret,';is_bakan')+ 8); 
$ret = substr($ret, strpos($ret,'</a>')+ 4); 

for($i=0; $i < 18; $i++)
{
    $linkBeg = strpos($ret,'<a href="');
    $linkEnd = strpos($ret,';is_bakan');
    $linkArr[$i] = 'http://tieba.baidu.com'.substr($ret, $linkBeg + 9, $linkEnd - $linkBeg - 9)."&pn=0";
    
    $idBeg = strpos($linkArr[$i],'kz=');
    $idEnd = strpos($linkArr[$i],'&'); 
    $idArr[$i] = substr($linkArr[$i], $idBeg + 3, $idEnd - $idBeg - 3);
    
    $titleBeg =  strpos($ret,'pinf');
    $titleEnd =  strpos($ret,'</a>'); 
    $titleArr[$i] = substr($ret, $titleBeg + 2, $titleEnd - $titleBeg - 2); 
      $titleArr[$i] = substr($titleArr[$i], strpos($titleArr[$i],'>') + 1);    
    $titleArr[$i] = str_replace("&#160;","",$titleArr[$i]);
    $titleArr[$i] = str_replace("#","",$titleArr[$i]);
     $titleArr[$i] = str_replace("【","『",$titleArr[$i]);       
     $titleArr[$i] = str_replace("】","』",$titleArr[$i]);  
    
    $tempBeg =  strpos($titleArr[$i],'.');
    $titleArr[$i] = substr($titleArr[$i], $tempBeg + 1); 
    
    $ret = substr($ret, $titleEnd + 4);
    
    
  
    /*    echo $titleArr[$i]."\n";
      echo $linkArr[$i]."\n" ;
     echo $idArr[$i]."\n";*/
}

//获取内容$conArr
//echo $linkArr[0]."\n";
/*for($j=0, $loop = 0, $pageBeg = 0, $conid = 0; $j < 18 && $conid < 1;)
{	
       $temp = file_get_contents($linkArr[$j]);
    if (!strstr($temp,'pn=30'))
    {
        //   echo "n";
        $j++;
        continue;
    }
    

    $content = $temp;
	$content = substr($content, strpos($content,'</table>'));    

	$count = substr_count($content, '<div class="i">');
    // echo $count;
	for($i=0; $i < $count; $i++)
	{
   		$conBeg = strpos($content,'<p>');
    	$conEnd = strpos($content,'<table>');
    	$ct = substr($content, $conBeg, $conEnd - $conBeg);
        $ct = str_replace("&#160;", "", $ct);
        $ct = str_replace("<br/>", '</p>', $ct);        
        $ct = str_replace('<div class="i">', "<p>", $ct); 
        $ct = substr($ct, strpos($ct, '<p>'));  
        $conArr[$conid] .= $ct;
    
   	 	$content = substr($content, $conEnd + 5);    
           
      
	}
    
    //   echo $linkArr[$j];
    $pageBeg += 30;
    //下一页
     if (strstr($content,'pn='.(string)$pageBeg) && $loop < 2)
	{
         //$pageBeg += 30;
        $linkArr[$j] = substr($linkArr[$j], 0, strpos($linkArr[$j],'&pn=') + 4).(string)$pageBeg;
     	$loop++;
	}
    else
    {     
        $titleCovArr[$conid] = $titleArr[$j];
        $idCovArr[$conid] = $idArr[$j];
        $j++;
        $loop = 0;
        $pageBeg = 0;
        $conid++;
    }
}*/

//echo $conArr[0]."\n";

//另一页
$ret = file_get_contents('http://tieba.baidu.com/mo/q----,sz@320_240-1-3---1/m?kw=%E6%88%92%E8%89%B2&lp=5011&lm=&pn=20');
$ret = substr($ret, strpos($ret,'</div>'));
$ret = substr($ret, strpos($ret,';is_bakan')+ 8);
$ret = substr($ret, strpos($ret,';is_bakan')+ 8); 
$ret = substr($ret, strpos($ret,'</a>')+ 4); 

for($i=18; $i < 36; $i++)
{
    $linkBeg = strpos($ret,'<a href="');
    $linkEnd = strpos($ret,';is_bakan');
    $linkArr[$i] = 'http://tieba.baidu.com'.substr($ret, $linkBeg + 9, $linkEnd - $linkBeg - 9)."&pn=0";
    
    $idBeg = strpos($linkArr[$i],'kz=');
    $idEnd = strpos($linkArr[$i],'&'); 
    $idArr[$i] = substr($linkArr[$i], $idBeg + 3, $idEnd - $idBeg - 3);
    
    $titleBeg =  strpos($ret,'pinf');
    $titleEnd =  strpos($ret,'</a>'); 
    $titleArr[$i] = substr($ret, $titleBeg + 2, $titleEnd - $titleBeg - 2); 
      $titleArr[$i] = substr($titleArr[$i], strpos($titleArr[$i],'>') + 1);    
    $titleArr[$i] = str_replace("&#160;","",$titleArr[$i]);
    $titleArr[$i] = str_replace("#","",$titleArr[$i]);
     $titleArr[$i] = str_replace("【","『",$titleArr[$i]);       
     $titleArr[$i] = str_replace("】","』",$titleArr[$i]);  
    
    $tempBeg =  strpos($titleArr[$i],'.');
    $titleArr[$i] = substr($titleArr[$i], $tempBeg + 1); 
    
    $ret = substr($ret, $titleEnd + 4);
    
    
  
    /*    echo $titleArr[$i]."\n";
      echo $linkArr[$i]."\n" ;
     echo $idArr[$i]."\n";*/
}


//随机输出
$numbers = range (1, $i-1); 
//shuffle 将数组顺序随即打乱 
shuffle($numbers); 
$numbersRand = array_slice($numbers, mt_rand(0, $i - 22), 20); 

$rss = new CyrilPerrin\Rss\Rss(
    'jieseba', 'http://tieba.baidu.com', '戒色吧', 'zh', '120', $_SERVER['REQUEST_TIME']
);

//读取18条 max = 18
for($i=0;$i<20;++$i)
{
    
    /*	$rss->addItem(
        'http://'.$idCovArr[$i].'/'.$_SERVER['REQUEST_TIME'], $titleCovArr[$i], 'http://'.$idCovArr[$i].'/'.$_SERVER['REQUEST_TIME'], $conArr[$i], 'Rank News',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', null
);*/
    $rss->addItem(
        'http://'.$idArr[$numbersRand[$i]].'/'.$_SERVER['REQUEST_TIME'], $titleArr[$numbersRand[$i]], 'http://1.jiese.applinzi.com/RSS-master/example/jieseana.php?idArr='.$idArr[$numbersRand[$i]], $conArr[$numbersRand[$i]], 'Rank News',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', null
);
}




echo $rss;


?>
