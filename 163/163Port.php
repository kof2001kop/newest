<?php

// Require
require('../vendor/CyrilPerrin/Rss/Rss.php');

// HTTP header
header('content-type:application/rss+xml; charset=utf-8');


//时间限制
$nowTimes = date("H");
if ($nowTimes < 6 || $nowTimes >= 23)
{
    
	$rss = new CyrilPerrin\Rss\Rss(
    $_GET['key'], 'https://www.163.com', '163', 'zh', '120', $_SERVER['REQUEST_TIME']);
 
	$rss->addItem(
        1, '『休息时间』', 'http://1.jiese.applinzi.com/rest.php', '', 'Zhihu',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', 'https://www.163.com');
  	
    echo $rss;
    
    return;
}

//次数限制30次
	use sinacloud\sae\Storage as Storage;
	$s = new Storage();
	$openCon = explode("@%", $s->getObject("kpr", "163Limit.txt")->{"body"});
	
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
			$s->putObject($updateT, "kpr", "163Limit.txt", array(), array('Content-Type' => 'text/txt'));     
        }
    }
	else
    {	
        $updateT = date('m/d', time());	
		$updateT .= "@%5";

		$s->putObject($updateT, "kpr", "163Limit.txt", array(), array('Content-Type' => 'text/txt'));
    }


//开始
$ret = file_get_contents('http://news.163.com/special/0001386F/rank_news.html');


/* 得到指定新闻的搜索起点  */
$ret = substr($ret,strrpos($ret,'tabContents active'), 20000);

//preg_match_all('/<a.*?href="http:\/\/news\.163\.com\/\d+\/\d+\/\d+\/[^>]*>([^<]*)<\/a>/i', $ret, $_match);
//echo $_match[0][1];

for($i=0, $j=0;$i < 15 && $j < 50; $j++)
{
    $ret = substr($ret, strpos($ret,'<a href="http://news.163.com/')+29);

	if (is_numeric($ret[0]) && $j > 0)
	{
	$newsIDArr[$i] = substr($ret, 11,  strpos($ret, '.html')-11);     // 匹配连接到newsIDArr 
	$beg = strpos($ret, ">") + 1;
	$titArr[$i] = iconv('GBK', 'UTF-8', substr($ret, $beg, strpos($ret, '<')-$beg));    // 匹配标题到titArr   
    //  匹配连接到评论comlinkArr  
    $comlinkArr[$i] = 'http://comment.news.163.com/api/v1/products/a2869674571f77b5a0867c3d71db5856/threads/'.$newsIDArr[$i].'/comments/hotTopList?offset=0&limit=40&showLevelThreshold=72&headLimit=1&tailLimit=2&callback=getData&ibc=newspc'; 
        //$linkModel[$i] =  'http://news.163.com/'.$newsIDArr[$i].$_SERVER['REQUEST_TIME'];
        $linkModel[$i] =  "http://1.jiese.applinzi.com/RSS-master/example/163ana.php?id=$newsIDArr[$i]";
    ++$i;
    }
}


///获取描述
/*
for($i=0; $i < 10;$i++)
{
    $coment =file_get_contents($comlinkArr[$i]);
    
    ///获取id与json的唯一分隔符']'的位置
    $onlyDiv = strpos($coment,'],');
    
    //// id, 其中idT[1]为全部所需id
    $id = substr($coment, 25, $onlyDiv-26);  
    $idT = explode('","',$id);
    
    /// 评论内容的json
    $json = json_decode(substr($coment, $onlyDiv + 13, -4));  

    // 遍历开始
    $loop = count($idT); 
    for ($j=0;$j< $loop; ++$j)
    {
        $div = explode(',',$idT[$j]);
        $effect = count($div);
        for ($m=0;$m<$effect; ++$m)
        {
            $vote = ($m == $effect - 1) ? '「'.$json->{$div[$m]}->{'vote'}.'」 ' : ''; 
       
            $deArr[$i] .= '<p>'.$vote.$json->{$div[$m]}->{'content'}.'</p>';
        }
         $deArr[$i] .= '<p>￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣</p>'; 
        //  ————————————————————————
                           
    }
}*/

//////////////////////////////////


$ret = file_get_contents('http://news.163.com/special/0001386F/rank_tech.html');


/* 得到指定新闻的搜索起点  */
$ret = substr($ret,strrpos($ret,'tabContents active'), 20000);

//preg_match_all('/<a.*?href="http:\/\/news\.163\.com\/\d+\/\d+\/\d+\/[^>]*>([^<]*)<\/a>/i', $ret, $_match);
//echo $_match[0][1];

for($ii=$i, $j=0;$ii < $i + 25 && $j < 65; $j++)
{
    $ret = substr($ret, strpos($ret,'<a href="http://tech.163.com/')+29);

	if (is_numeric($ret[0]) && $j > 0)
	{
	$newsIDArr[$ii] = substr($ret, 10,  strpos($ret, '.html')-10);     // 匹配连接到newsIDArr 
	$beg = strpos($ret, ">") + 1;
	$titArr[$ii] = iconv('GBK', 'UTF-8', substr($ret, $beg, strpos($ret, '<')-$beg));    // 匹配标题到titArr   
    //  匹配连接到评论comlinkArr  
    $comlinkArr[$ii] = 'http://comment.news.163.com/api/v1/products/a2869674571f77b5a0867c3d71db5856/threads/'.$newsIDArr[$ii].'/comments/hotTopList?offset=0&limit=40&showLevelThreshold=72&headLimit=1&tailLimit=2&callback=getData&ibc=newspc'; 
        $linkModel[$ii] =  "http://1.jiese.applinzi.com/RSS-master/example/163ana.php?id=$newsIDArr[$ii]";
    ++$ii;
    }
}


///娱乐
$ret = file_get_contents('http://news.163.com/special/0001386F/rank_ent.html');


/* 得到指定新闻的搜索起点  */
$ret = substr($ret,strrpos($ret,'tabContents active'), 20000);

//preg_match_all('/<a.*?href="http:\/\/news\.163\.com\/\d+\/\d+\/\d+\/[^>]*>([^<]*)<\/a>/i', $ret, $_match);
//echo $_match[0][1];

for($ik=$ii, $j=0;$i < $ii + 25 && $j < 65; $j++)
{
    $ret = substr($ret, strpos($ret,'<a href="http://ent.163.com/')+29);

	if (is_numeric($ret[0]) && $j > 0)
	{
	$newsIDArr[$ik] = substr($ret, 10,  strpos($ret, '.html')-10);     // 匹配连接到newsIDArr 
	$beg = strpos($ret, ">") + 1;
	$titArr[$ik] = iconv('GBK', 'UTF-8', substr($ret, $beg, strpos($ret, '<')-$beg));    // 匹配标题到titArr   
    //  匹配连接到评论comlinkArr  
    $comlinkArr[$ik] = 'http://comment.news.163.com/api/v1/products/a2869674571f77b5a0867c3d71db5856/threads/'.$newsIDArr[$ik].'/comments/hotTopList?offset=0&limit=40&showLevelThreshold=72&headLimit=1&tailLimit=2&callback=getData&ibc=newspc'; 
        //  $linkModel[$i] =  'http://ent.163.com/'.$newsIDArr[$i].$_SERVER['REQUEST_TIME'];
        $linkModel[$ik] =  "http://1.jiese.applinzi.com/RSS-master/example/163ana.php?id=$newsIDArr[$ik]";
    ++$ik;
    }
}


//随机输出
$numbers = range (1, $ik-1); 
//shuffle 将数组顺序随即打乱 
shuffle($numbers); 
$numbersRand = array_slice($numbers, mt_rand(0, $ik - 41), 39); 


$rss = new CyrilPerrin\Rss\Rss(
    '163 News', 'http://news.163.com/special/0001386F/rank_news.html', '跟帖排行榜', 'zh', '120', $_SERVER['REQUEST_TIME']
);
//$rss->setImage('Tux', 'tux.png', 'http://www.example.org');

//读取20条新闻
$rss->addItem(
    0, '网易跟帖榜', 'http://1.jiese.applinzi.com/163_tie.php', '', 'Rank News',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', $linkModel[$i]
);
for($i=0;$i < 19;++$i)
{
    
	$rss->addItem(
    $i + 1, $titArr[$numbersRand[$i]], $linkModel[$numbersRand[$i]], '', 'Rank News',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', $linkModel[$numbersRand[$i]]
);
}


echo $rss;



?>
