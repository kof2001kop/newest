<?php

// Require
//require('RSS-master/vendor/CyrilPerrin/Rss/Rss.php');

// HTTP header
header('content-type:text/html; charset=utf-8');//charset=utf-8

	//选择分割线
	use sinacloud\sae\Storage as Storage;
	$sd = new Storage();
	$divSize = $sd->getObject("kpr", "div.txt")->{"body"};
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

for ($i = 0, $j = 0; $i < 1; $i++, $j += 20)
{
    //http://comment.api.163.com/api/v1/products/a2869674571f77b5a0867c3d71db5856/recommendList/single?offset=0&limit=30&ibc=newspc&callback=jQuery110201860289060432352_1465656856668
	$urls[$i] = "http://comment.api.163.com/api/v1/products/a2869674571f77b5a0867c3d71db5856/recommendList/single?offset=$j&limit=20&ibc=newspc";

	$ch[$i] = curl_init("http://comment.api.163.com/api/v1/products/a2869674571f77b5a0867c3d71db5856/recommendList/single?offset=$j&limit=20&ibc=newspc");
	curl_setopt($ch[$i], CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)");
	curl_setopt($ch[$i], CURLOPT_HEADER, 0); 
	curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch[$i], CURLOPT_CONNECTTIMEOUT, 5);     

	$ret[$i] = curl_exec($ch[$i]);
	$ret[$i] = json_decode($ret[$i], true);
	curl_close($ch[$i]);    
}


//$divi = "￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣";
$save = '';
$save .= '<p>';
for ($loop = 0; $loop < 1; $loop++)
{   
    $ic = 0;
	foreach ($ret[$loop] as $val)
    {//.$ret[$loop][$ic]["comments"][0][1]["user"]["nickname"]
        
    	//彩色输出
        if ($ic % 2 == 0)
        {
            $save .= "🔸 ";
        }
        else
        {
            $save .= "🔹 ";
        }
        
		$save .= $ret[$loop][$ic]["thread"]["title"]."<br><br>". 
        '『'.$ret[$loop][$ic]["comments"][0][1]["vote"].'』 '.$ret[$loop][$ic]["comments"][0][1]["content"]."".
        // 'pubDate:'.$ret[$loop][$ic]["comments"][0][1]["createTime"]. 
	 "<br><br>$divi<br>";
         
		$ic++;
	}
}
//$save .= '</p>';
////////////////////////


for ($i = 0, $j = 0; $i < 1; $i++, $j += 5)
{
    //$urls1[$i] = "http://comment.api.163.com/api/v1/products/a2869674571f77b5a0867c3d71db5856/recommendList/build?offset=$j&limit=10&showLevelThreshold=100&headLimit=100&tailLimit=100&ibc=newspc&callback";

	$ch1[$i] = curl_init("http://comment.api.163.com/api/v1/products/a2869674571f77b5a0867c3d71db5856/recommendList/build?offset=$j&limit=5&showLevelThreshold=100&headLimit=100&tailLimit=100&ibc=newspc&callback"
);
	curl_setopt($ch1[$i], CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)");
	curl_setopt($ch1[$i], CURLOPT_HEADER, 0); 
	curl_setopt($ch1[$i], CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1[$i], CURLOPT_CONNECTTIMEOUT, 5);     

	$ret1[$i] = curl_exec($ch1[$i]);
    $ret1[$i] = json_decode($ret1[$i], true);
	curl_close($ch1[$i]);    
}

for ($loop = 0; $loop < 1; $loop++)
{
	for ($j = 0; $j < count($ret1[$loop]); $j++)
	{
		$sum = count($ret1[$loop][$j]["comments"][0]);
		$ic = 0;
        
        //彩色输出
        if ($j % 2 == 0)
        {
            $save .= "🔸 ";
        }
        else
        {
            $save .= "🔹 ";
        }
        
        $save .= $ret1[$loop][$j]["thread"]["title"]."<br>";   
		foreach ($ret1[$loop][$j]["comments"][0] as $val)
		{
    		$save .= '<br>'.($ic + 1 == $sum ? ('「'.(string)$val["vote"].'」 ') : '').$val["content"].'<br>';
    		$ic++;
		}

		$save .= "<br>$divi<br>";
	}
}


//显示次数限制
$s = new Storage();
$openCon = explode("@%", $s->getObject("kpr", "163Limit.txt")->{"body"});
$save .= "📊 $openCon[1]</p>";

echo $save;
?>
