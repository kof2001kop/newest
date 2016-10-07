<?php

	header('content-type:text/html; charset=utf-8');//charset=utf-8
//页数分离
$result = explode('/',$_GET["id"]);
$getID = $result[0];
$sPage = $result[1];
$titles = base64_decode(base64_decode($result[2]));

$cookie_arr = array(
		'__utma' => '51854390.547175385.1465103152.1465103152.1465103152.1',
		'__utmb' => '51854390.4.10.1465103152',
		'__utmc' => '51854390',
		'__utmv' => '51854390.100-1|2=registration_date=20141126=1^3=entry_date=20141126=1',
		'__utmz' => '51854390.1465103152.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)',
		'_xsrf' => '35a10f6bcead0e69f14b00607866bf98',
		'_za' => '	7776c5b1-8a92-4184-98ea-94d91f8bd5b0',
		'_zap' => '9b92a5f1-a9eb-4aec-a170-f69b313d4a20',
    /**/'_zap' => '9349f159-e616-4d41-9799-11ee42b5c5eb',
		'cap_id' => '"ZGM2ZWIwNWQ5MDA4NDc4Y2E5YTM3ZDBlNGRmM2MwNGM=|1465103148|2cd8a5bf03efc76882b9b2b8834518eb89163d3d"',
		'd_c0' => '"AGAAeabQBwqPTqFiOFPcAndJwnLwUe_cCVI=|1465103150"',
		'l_cap_id' => '"MGE1ZjNjY2UyNTU0NGRjNjk0ZWZkZTAxMzJjZjBlMWM=|1465103148|e161e0f396aa88ad720a1288cd10f0e5f3175a54"',
		'l_n_c' => '1',
		'login' => '"NDAxZGJiZGQ3ZjEwNDk2Yjk5NWRmYjBmMzRlNzRiZTI=|1465103171|e76438bf82a982dcaecc5853e85eccc28742ca88"',
		'q_c1' => '	841b0eab49e2456f9a691673c8c64ca2|1465103148000|1465103148000',
    's-i' => '6',//
    's-q' => '%E6%85%A2%E6%80%A7%E8%83%83%E7%82%8E',//
    's-t' => 'autocomplete',//
    'sid' => 'e63rlk6q',//
		'z_c0' => '	Mi4wQUFDQW9QRkJBQUFBWUFCNXB0QUhDaGNBQUFCaEFsVk5VMEI3VndDWHZXZEFVZjNhQ1oyb1dmeEwtSTN6RC1QWE1n|1465103187|a007e3a57dd4f0552a748a34187bb9ba45942ddc'
	);
$cookie = '';
		foreach ($cookie_arr as $key => $value) {
			if($key != 'z_c0')
				$cookie .= $key . '=' . $value . ';';
			else
				$cookie .= $key . '=' . $value;
		}
$ch = curl_init();
$timeout = 15;
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
curl_setopt($ch, CURLOPT_URL, 'https://www.zhihu.com/node/QuestionAnswerListV2');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
/////
$save = array();
for ($i = $sPage * 40; $i < ($sPage * 40) + 40; $i += 10) //显示40条
{
	$data = array(
				'_xsrf' => '35a10f6bcead0e69f14b00607866bf98',
				'method' => 'next',
				'params' => "{\"url_token\":$getID,\"pagesize\":10,\"offset\":$i}"
			 	);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	$ret = curl_exec($ch);
   
    $ret = json_decode($ret)->msg;
    $save = array_merge($save, $ret);
}
curl_close($ch);
//print_r($save);
$i = 0;
$combine = "<html>
<head>
<meta charset=\"GBK\">
<div>$getID/$sPage</div>
<body>";

    
$divSize = file_get_contents('http://jiese-kpr.stor.sinaapp.com/div.txt');
if ($divSize == 3)
{
    $divi = "─────────────";
}
else if ($divSize == 1)
{
    $divi = "─────────────────────";
}
else
{ 
    $divi = "────────────────";
    
}


	//显示次数限制30次
	use sinacloud\sae\Storage as Storage;
	$sR = new Storage();
	$openCon = explode("@%", $sR->getObject("test", "zhihuLimit.txt")->{"body"});


$sumS = count($save);
foreach($save as &$value)
{ 
    //	$value = iconv("utf-8", "gbk", $value);
	
    
	if (strpos($value,'author-link'))
	{
		$value = substr($value, strpos($value,'author-link')); 
		$urlBeg = strpos($value,'>');
		$urlEnd = strpos($value,'</a>');
		$author[$i] = substr($value, $urlBeg + 1, $urlEnd - $urlBeg - 1);   //作者
	}
	else
	{
        $author[$i] = "匿名";
        // $author[$i] = "匿名";
	}
    //echo $author[$i];
  
  
    $urlBeg = strpos($author[$i],'>');
    if ($urlBeg)
    {
    	$author[$i] = substr($author[$i], $urlBeg + 1); 
        
        $urlEnd = strpos($author[$i],'<');
        if ($urlEnd)
        {
            $author[$i] = substr($author[$i], 0, $urlEnd); 
        }
    }
    
    if(strpos($author[$i],'<') !== false || strpos($author[$i],'>') !== false)
    {
       $author[$i] = "匿名";
    }
    
    
    
    $value = substr($value, strpos($value,'data-votecount="'));
    $urlBeg = strpos($value,'data-votecount="');
    $urlEnd = strpos($value,'">');
    $vote[$i] = substr($value, $urlBeg + 16, $urlEnd - $urlBeg - 16);   //赞同数
    
    $value = substr($value, strpos($value,'zm-editable-content clearfix'));   
    $urlBeg = strpos($value,'>');
    $urlEnd = strpos($value,'</div>');
    $content[$i] = substr($value, $urlBeg + 1, $urlEnd - $urlBeg - 1);   //内容  
    $content[$i] = preg_replace( "/<img.*?>/si", '',$content[$i]);
    $content[$i] = preg_replace( '/\<noscript>(.*?)\<\/noscript>/', '',$content[$i]);   
     $content[$i] = str_replace("<br><br><br><br><br>","<br><br>",$content[$i]);     
    $content[$i] = str_replace("<br><br><br><br>","<br><br>",$content[$i]);  
    $content[$i] = str_replace("<br><br><br>","<br><br>",$content[$i]);  
	$content[$i] = str_replace("<br><br>","kprr",$content[$i]);    
	$content[$i] = str_replace("<br>","<br><br>",$content[$i]);  
	$content[$i] = str_replace("kprr","<br><br>",$content[$i]);   
    $content[$i] = str_replace("<br><br><br><br>","<br><br>",$content[$i]);
    
  	$begbr = substr($content[$i], 2, 2);
  	$begbr1 = substr($content[$i], 6, 2);    
    
    if ((strcmp($begbr, 'br') == 0) && (strcmp($begbr1, 'br') == 0))
    {
        //  $begbr = 'tyty';
         $content[$i] = substr($content[$i], 9);
    }
    
    $value = substr($value, strpos($value,'zg-anchor-hidden'));  
    $urlBeg = strpos($value,'name="');    
    $urlEnd = strpos($value,'-comment');   
    $commentID[$i] = substr($value, $urlBeg + 6, $urlEnd - $urlBeg - 6);   //内容  
    if (!is_numeric($commentID[$i][1]))
        $commentID[$i] = "non";
    
    $value = substr($value, strpos($value,'z-icon-comment')); 
    $urlBeg = strpos($value,'</i>');    
    $urlEnd = strpos($value,' ');
    $comNum[$i] =  substr($value, $urlBeg + 4, $urlEnd - $urlBeg - 4);   //内容  
    if (!is_numeric($comNum[$i]))
        $commentID[$i] = "non";
    
    
    $lc = substr_count($content[$i],"<");
    $rc = substr_count($content[$i],">");   
    
    if (($lc + $rc) % 2)
    {
        $content[$i] = "";
        continue;
    }
    
    if ($sumS - 1== $i)
        $addEnd = "<br>📜  $sPage"."　📊 $openCon[1]";
    else
        $addEnd = "";
    
    $combine .= "<p>".chr(($i%26+65)).".『".$vote[$i].'』'.$author[$i].'</p><p>'.$content[$i]."</p><p>$divi$addEnd</p>";
    
    
    $i++;
} 

if (empty($save))
{   
    echo "<p>此页空~</p><p>$divi<br>📜  $sPage"."　📊 $openCon[1]</p>";
}

$combine .= "</body></html>";

echo $combine;

//整理评论

$comPage = '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0"><channel><title>43387492</title><link>https://www.zhihu.com</link><description>评论</description><category>zh</category><language>120</language><ttl>1473857073</ttl>';

$comPage .= "<item><guid>$j</guid><title>题目：$titles</title><link>http://1.jiese.applinzi.com/rest.php</link><description>$j</description><category>zhihu</category><author>Cyril</author><pubDate>Wed, 14 Sep 2016 21:20:57 +0800</pubDate><comments>Comments</comments></item>";
for ($j = 0; $j < $i; $j++)
{
    $author[$j] = $author[$j];
    if ($commentID[$j] == "non")
    {  
        $isCom = "『non』";
        continue;
    }
    else
        $isCom = "『$comNum[$j]』";
    $comPage .= "<item><guid>$j</guid><title>".chr(($j%26+65)).". 『$author[$j]』$isCom</title><link>http://1.jiese.applinzi.com/zhihuComment.php?id=$commentID[$j]</link><description>$j</description><category>zhihu</category><author>Cyril</author><pubDate>Wed, 14 Sep 2016 21:20:57 +0800</pubDate><comments>Comments</comments></item>";
}

  $comPage .= "</channel></rss>";

//use sinacloud\sae\Storage as Storage;
//echo $comPage;
$s = new Storage();
$s->putObject($comPage, "test", "zhihu.xml", array(), array('Content-Type' => 'text/xml'));

?>
