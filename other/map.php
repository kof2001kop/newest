<?php
header('content-type:text/html; charset=utf-8');//charset=utf-8

	//选择分割线
	use sinacloud\sae\Storage as Storage;
	$s = new Storage();
	$divSize = $s->getObject("kpr", "div.txt")->{"body"};
	if ($divSize == 3)
	{
        $divs = '─────────────';
    }
	else if ($divSize == 1)
	{
        $divs = '￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣';
	}
	else
    {
          $divs = '￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣￣';
    }

//    echo "<p>$_GET['beg']$_GET['end'] </p>";
$result = explode("kpr", $_GET['beg']);
$beg = urlencode(base64_decode(base64_decode($result[0])));
$end = urlencode(base64_decode(base64_decode($result[1])));
///$beg = $_GET['beg'];
//$end = $_GET['end'];

//$r = file_get_contents('http://api.map.baidu.com/direction/v1?mode=transit&origin=康乐村&destination=西门口&region=广州&output=json&ak=ZCxksjmDskWrWKLv6rNQHfWA5GSzOAOi');///
$r = file_get_contents('http://api.map.baidu.com/direction/v1?mode=transit&origin='.$beg.'&destination='.$end.'&region=广州&output=json&ak=ZCxksjmDskWrWKLv6rNQHfWA5GSzOAOi');

if (!$r)
{   
    echo "<p>地址错误</p>";
    return;
}
//var_dump(json_decode($r));

$ansSize = count(json_decode($r)->{"result"}->{"routes"});
if(!$ansSize)
{
    echo "<p>$ansSize请输入准确地址</p>";
    return;
}
	
$numT = array("①","②","③", '④', '⑤', '⑥', '⑦', '⑧', '⑨', '⑩', '⑪', '⑫', '⑬', '⑭', '⑮', '⑯', '⑰', '⑱', '⑲', '⑳');	
//echo var_dump(json_decode($r)->{"result"}->{"routes"}[0]->{"scheme"}[0]->{"arrive_time"}).'<br>';////

echo '<p>';
echo urldecode($beg).' ➡ '.urldecode($end)."<br><br>$divs<br>";
for ($i = 0; $i < $ansSize; $i++)
{
    $stepSize = count(json_decode($r)->{"result"}->{"routes"}[$i]->{"scheme"}[0]->{"steps"});
    $usetime = explode("T", json_decode($r)->{"result"}->{"routes"}[$i]->{"scheme"}[0]->{"arrive_time"});
    
    if ($i % 2 == 0)  //颜色
    {
       $answer[$i] .= "🔸";
    }
    else
    {
       $answer[$i] .= "🔹";
    }
    
    $answer[$i] .=  $usetime[1].'<br><br>';
    
    for ($j = 0; $j < $stepSize; $j++)
    {
        $answer[$i] .= $numT[$j].' ';
         $answer[$i] .= json_decode($r)->{"result"}->{"routes"}[$i]->{"scheme"}[0]->{"steps"}[$j][0]->{"stepInstruction"}.'<br><br>';
    }
    
}

for ($i = 0; $i < $ansSize; $i++)
{
    $answer[$i] = str_replace(",","，", $answer[$i]); 
    $answer[$i] = str_replace("(","『", $answer[$i]); 
    $answer[$i] = str_replace(")","』", $answer[$i]); 
    echo $answer[$i]."$divs<br>";
}

echo '</p>';
//var_dump(json_decode($r)->{"result"}->{"routes"}[1]->{"scheme"}[0]->{"steps"}[1][0]->{"stepInstruction"});

?>
