<?php

// HTTP header
header('content-type:text/html; charset=utf-8');//charset=utf-8
        	//打开
		use sinacloud\sae\Storage as Storage;
	$randT = array(1, 1, 1, 1, 1, 1, 1, 1, 2, 2, 2);
//echo round(floor((60 - date('i',time()))) / 60, 1);
	if ($randT[array_rand($randT, 1)] == 1)
    {

	//	header('content-type:text/html; charset=utf-8');//charset=utf-8

    $ch = curl_init();
    $url = 'http://apis.baidu.com/txapi/dictum/dictum';
    $header = array(
        'apikey: 36f45f8ea82f60caa31edda1181a05e9',
    );
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);

    $json = json_decode($res);

	echo "<p>『".$json->{'newslist'}[0]->{"mrname"}."』 ".$json->{'newslist'}[0]->{"content"}."<br><br><br>";

//	echo "『😪💤』　23：00~ 06：00<br>";


	echo "🕛 ".(((365 - date('z',time())) * 24) - date('H',time()) + round(floor((60 - date('i',time()))) / 60, 1))." <br></p>";
    }
	else
    {

		$s = new Storage();

		$openCon = explode("\n", $s->getObject("kpr", "feixiang.txt")->{"body"});
	
		while (1)
    	{
            $temp = $openCon[mt_rand(2, 1808)];
        	if (is_numeric($temp[1]))
        	{
                $temp = iconv('gbk','utf-8', $temp);
                $tempCon = explode(".", $temp);
                echo "<p>".$tempCon[1]."<br><br><br>";
				echo "🕛 ".(((365 - date('z',time())) * 24) - date('H',time()) + round(floor((60 - date('i',time()))) / 60, 1))." <br></p>";
            	break;
        	}
    	}
    }


?>
