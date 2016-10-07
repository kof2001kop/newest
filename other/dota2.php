<?php

	header('content-type:text/html; charset=utf-8');//charset=utf-8
	$numT = array("①","②","③", '④', '⑤', '⑥', '⑦', '⑧', '⑨', '⑩', '⑪', '⑫', '⑬', '⑭', '⑮', '⑯', '⑰', '⑱', '⑲', '⑳');	

	//打开
	use sinacloud\sae\Storage as Storage;
	$s = new Storage();
	$openRank = explode("@%", $s->getObject("kpr", "dota2Rank.txt")->{"body"});

	//打开数组转下标
	for ($i = 1; $i < 21; $i++)
    {   
        $arrRank[$openRank[$i]] = $i - 1;
    }
//var_dump($arrRank);	

	if (time()- $openRank[0] <= 86400)          //输出昨天数据   
    {
        $updateT = date('m/d h:i a', $openRank[0]);	
       
        		echo "<p>排名 　 积分　　　玩家<br>";
        	echo "────────────────<br>";
        //echo "<p><br>";
	
		for ($i = 0; $i < 20; $i++)
    	{      
        	$upFlag = " 　";
    
        	if ($openRank[$i + 41] != 'F')
       		 {
            	$upFlag = " ↾";//⇧
            	$upFlag .= $openRank[$i + 41];
        	}

       
        	echo $numT[$i]."$upFlag"."　 ".$openRank[$i+21]." 　 ".$openRank[$i+1]."<br>"; //①    
   		}
	
		echo "────────────────<br>📆　$updateT</p>";
        
        return;
    }

//var_dump($openRank);echo "<br>";


	//国服
	$dota2 = file_get_contents('http://www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=china');

	//file错误
	if (!$dota2)
	{
   	 	echo '<p>地址错误~</p>';
    	return;
	}
	$json = json_decode($dota2);
	
	$updateTime = date('m/d h:i a', $json->{'time_posted'});

	for ($i = 0; $i < 10; $i++)
    {
		$Rank[$i] = $json->{'leaderboard'}[$i]->{"rank"};   //排名
        $tt = $json->{'leaderboard'}[$i]->{"team_tag"};
        if (!empty($tt))
        {
            $tt .= ".";
        }
        $Player[$i] = $tt.($json->{'leaderboard'}[$i]->{"name"});   //名字
 		$Mmr[$i] = $json->{'leaderboard'}[$i]->{"solo_mmr"};   //天梯分            
    }

	//欧服
	$dota2 = file_get_contents('http://www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=europe');
	//file错误
	if (!$dota2)
	{
   	 	echo '<p>地址错误~</p>';
    	return;
	}
	$json = json_decode($dota2);

	$is = $i;
	for ($i = 0; $i < 10; $i++)
    {
		$Rank[$i + $is] = $json->{'leaderboard'}[$i]->{"rank"};   //排名
        $tt = $json->{'leaderboard'}[$i]->{"team_tag"};
        if (!empty($tt))
        {
            $tt .= ".";
        }
		$Player[$i + $is] = $tt.($json->{'leaderboard'}[$i]->{"name"});   //名字
 		$Mmr[$i + $is] = $json->{'leaderboard'}[$i]->{"solo_mmr"};   //天梯分            
    }

	//欧服
	$dota2 = file_get_contents('http://www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=americas');
	//file错误
	if (!$dota2)
	{
   	 	echo '<p>地址错误~</p>';
    	return;
	}
	$json = json_decode($dota2);

	$is = $i + $is ;
	for ($i = 0; $i < 10; $i++)
    {
		$Rank[$i + $is] = $json->{'leaderboard'}[$i]->{"rank"};   //排名
        $tt = $json->{'leaderboard'}[$i]->{"team_tag"};
        if (!empty($tt))
        {
            $tt .= ".";
        }
		$Player[$i + $is] = $tt.($json->{'leaderboard'}[$i]->{"name"});   //名字
 		$Mmr[$i + $is] = $json->{'leaderboard'}[$i]->{"solo_mmr"};   //天梯分            
    }


	//东南亚
	$dota2 = file_get_contents('http://www.dota2.com/webapi/ILeaderboard/GetDivisionLeaderboard/v0001?division=se_asia');
	//file错误
	if (!$dota2)
	{
   	 	echo '<p>地址错误~</p>';
    	return;
	}
	$json = json_decode($dota2);

	$is = $i + $is ;
	for ($i = 0; $i < 10; $i++)
    {
		$Rank[$i + $is] = $json->{'leaderboard'}[$i]->{"rank"};   //排名
        $tt = $json->{'leaderboard'}[$i]->{"team_tag"};
        if (!empty($tt))
        {
            $tt .= ".";
        }
		$Player[$i + $is] = $tt.($json->{'leaderboard'}[$i]->{"name"});   //名字
 		$Mmr[$i + $is] = $json->{'leaderboard'}[$i]->{"solo_mmr"};   //天梯分            
    }

	//排序
	for ($i = 0; $i < 20; $i++)
    {
        $key = array_search(max($Mmr), $Mmr);
        $newRank[$i] = $Rank[$key];
        $newPlayer[$i] = $Player[$key];
        $newMmr[$i] = $Mmr[$key];
        $Mmr[$key] = 0;
    }


//$numT = array("①","②","③", '④', '⑤', '⑥', '⑦', '⑧', '⑨', '⑩', '⑪', '⑫', '⑬', '⑭', '⑮', '⑯', '⑰', '⑱', '⑲', '⑳');
	//$numTA = array('❶ ', '❷ ', '❸ ', '❹ ','❺ ' );
	echo "<p>排名 　 积分　　　玩家<br>";
echo "────────────────<br>";
//echo "<p><br>";
	
	for ($i = 0; $i < 20; $i++)
    {      
        $upFlag = " 　";
    
        if ($arrRank[$newPlayer[$i]] !== false && $arrRank[$newPlayer[$i]] > $i)
        {
            $upFlag = " ↾";//⇧
            $upFlag .= ($arrRank[$newPlayer[$i]] - $i);
            $arrUp[$i] = $arrRank[$newPlayer[$i]] - $i;
        }
        else
        {
            $arrUp[$i] = 'F';
        }
       
        echo $numT[$i]."$upFlag"."　 ".$newMmr[$i]." 　 ".$newPlayer[$i]."<br>"; //①
        
    }
	

echo "────────────────<br>📆：$updateTime</p>";

    $saveRank .= $json->{'time_posted'} ;
    $saveRank .= "@%"; 

	foreach($newPlayer as &$value)
    {
        $saveRank .= $value;
        $saveRank .= "@%";        
    }

	foreach($newMmr as &$value)
    {
        $saveRank .= $value;
        $saveRank .= "@%";        
    }


	foreach($arrUp as &$value)
    {
        $saveRank .= $value;
        $saveRank .= "@%";        
    }	

	//echo $comPage;
	$s->putObject($saveRank, "kpr", "dota2Rank.txt", array(), array('Content-Type' => 'text/txt'));
?>
