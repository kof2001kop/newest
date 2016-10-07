<?php
header('content-type:text/html; charset=utf-8');//charset=utf-8

$idArr = $_GET['idArr'];
$linkArr = 'http://tieba.baidu.com/mo/q---F4B5C5D01E52F7B5A7162564DD4410A9%3AFG%3D1-sz%40320_240%2C-1-3-0--2--wapp_1472992358721_427/m?kz='.$idArr.'&amp&pn=0';

//获取内容$conArr
//echo $linkArr[0]."\n";
for($j=0, $loop = 0, $pageBeg = 0, $conid = 0; $j < 1 && $conid < 1;)
{	
       $temp = file_get_contents($linkArr);
    /*  if (!strstr($temp,'pn=30'))
    {
        //   echo "n";
        $j++;
          continue;
    }*/
    

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
        //$conArr[$conid] .= $ct;
    
        //后期add
		$dotPos = strpos($ct, '.');
		$dotLeft = substr($ct, 0, $dotPos - 3);
		$dotRight = substr($ct, $dotPos + 1);
		$dealRes = $dotLeft.'.  '.$dotRight;
        $conArr[$conid] .= $dealRes;
        
        
   	 	$content = substr($content, $conEnd + 5);         
      
	}
    
    // 	echo $linkArr;
   
    $pageBeg += 30;
    //下一页
     if (strstr($content,'pn='.(string)$pageBeg) && $loop < 4)
	{
         //$pageBeg += 30;
        $linkArr = substr($linkArr, 0, strpos($linkArr,'&pn=') + 4).(string)$pageBeg;
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
}

  echo $conArr[$conid - 1];

//显示次数限制
	use sinacloud\sae\Storage as Storage;
	$s = new Storage();
	$openCon = explode("@%", $s->getObject("kpr", "jieseLimit.txt")->{"body"});
echo "<p>📊 $openCon[1]</p>";
?>
