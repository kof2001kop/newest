<?php
// HTTP header
//header('content-type:Text/HTML; charset=utf-8');
header('content-type:text/html; charset=utf-8');//charset=utf-8

//
//	$divSize = file_get_contents('http://jiese-kpr.stor.sinaapp.com/div.txt');
	//选择分割线
	use sinacloud\sae\Storage as Storage;
	$sd = new Storage();
	$divSize = $sd->getObject("kpr", "div.txt")->{"body"};
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

	$comlinkArr = 'http://comment.news.163.com/api/v1/products/a2869674571f77b5a0867c3d71db5856/threads/'.$_GET['id'].'/comments/hotTopList?offset=0&limit=40&showLevelThreshold=72&headLimit=1&tailLimit=2&callback=getData&ibc=newspc' ;
	
	$coment = file_get_contents($comlinkArr);
	//file错误
	if (!$coment)
	{	
        echo "<p>地址错误~</p>";
    	return;
	}
    
    ///获取id与json的唯一分隔符']'的位置
    $onlyDiv = strpos($coment,'],');
    
    //// id, 其中idT[1]为全部所需id
    $id = substr($coment, 25, $onlyDiv-26);  
    $idT = explode('","',$id);
    
    /// 评论内容的json
    $json = json_decode(substr($coment, $onlyDiv + 13, -4));  


    // 遍历开始
	echo '<p>';
    $loop = count($idT); 
    for ($j=0;$j< $loop; ++$j)
    {
        $div = explode(',',$idT[$j]);
        $effect = count($div);
        for ($m=0;$m<$effect; ++$m)
        {
            $vote = ($m == $effect - 1) ? '『'.$json->{$div[$m]}->{'vote'}.'』 ' : ''; 
       
            $deArr .= $vote.$json->{$div[$m]}->{'content'}.'<br><br>';
        }
         $deArr .= "$divs<br>";                             
    }

	echo $deArr;

//显示次数限制
	$s = new Storage();
	$openCon = explode("@%", $s->getObject("kpr", "163Limit.txt")->{"body"});

echo "📊 $openCon[1]</p>";

?>
