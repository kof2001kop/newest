<?php

	header('content-type:text/html; charset=utf-8');//charset=utf-8

	$comlinkArr = 'http://www.zhihu.com/r/answers/'.$_GET['id'].'/comments';

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

    $coment = file_get_contents($comlinkArr);
	//file错误
	if (!$coment)
	{
        echo '<p>地址错误~</p>';
        return;
    }
	
	$json = json_decode($coment);

    echo '<p>';
	for ($i = 0 ; $i < $json->{'paging'}->{'totalCount'} && $i < 30; $i++)
    {

        
        $likesCount = $json->{'data'}[$i]->{'likesCount'};
        
        if ($i % 2 == 0)  //颜色
        {
            echo "🔸";
        }
        else
        {
            echo "🔹";
        }
        
        
        if ($likesCount)   //赞数
        {
            echo '『'.$likesCount.'』';
        }
        else
        {
            echo ' ';
        }
        
        $name = $json->{'data'}[$i]->{'author'}->{'name'};    //名字
        
        
        if ($name == '知乎用户')
            echo '匿名';
        else
            echo $name;
        
        $reName = $json->{'data'}[$i]->{'inReplyToUser'}->{'name'};   //是否回复
        
        $isRe = ' ';
       	if ($reName)
        {
            if ($reName == '知乎用户')
                $isRe =  '👉 匿名 :　';
            else
                $isRe =  "👉 ".$reName.' :　';
            
        }
        
        echo '<br>'.$isRe.$json->{'data'}[$i]->{'content'}."<br><br>$divs<br>";
    }


	if ($json->{'paging'}->{'totalCount'} > 30)
    {
        $comlinkArr = 'http://www.zhihu.com/r/answers/'.$_GET['id'].'/comments?page=2';
    	$coment = file_get_contents($comlinkArr);
	
		$json = json_decode($coment);
        
        for ($i = 0 ; $i < ($json->{'paging'}->{'totalCount'} - 30) && $i < 30; $i++)
    	{
            
            // echo '<p>';
        
        $likesCount = $json->{'data'}[$i]->{'likesCount'};
        
        if ($i % 2 == 0)  //颜色
        {
            echo "🔸";
        }
        else
        {
            echo "🔹";
        }  
            
        if ($likesCount)   //赞数
        {
            echo '『'.$likesCount.'』';
        }
        else
        {
            echo ' ';
        }    
            
        $name = $json->{'data'}[$i]->{'author'}->{'name'};
            
 
        if ($name == '知乎用户')
            echo '匿名';
        else
            echo $name;
        
        $reName = $json->{'data'}[$i]->{'inReplyToUser'}->{'name'};
            
        $isRe = ' ';         
       	if ($reName)
        {
            if ($reName == '知乎用户')
                $isRe =  '👉 匿名 :　';
            else
                $isRe =  "👉 ".$reName.' :　';
            
        }   
        
            echo '<br>'.$isRe.$json->{'data'}[$i]->{'content'}."<br><br>$divs<br>";
   	 	}
    }

	if ($json->{'paging'}->{'totalCount'} > 60)
    {
        $comlinkArr = 'http://www.zhihu.com/r/answers/'.$_GET['id'].'/comments?page=3';
    	$coment = file_get_contents($comlinkArr);
	
		$json = json_decode($coment);
        
        for ($i = 0 ; $i < ($json->{'paging'}->{'totalCount'} - 60) && $i < 30; $i++)
    	{
            
            // echo '<p>';
        
        $likesCount = $json->{'data'}[$i]->{'likesCount'};
        
        if ($i % 2 == 0)  //颜色
        {
            echo "🔸";
        }
        else
        {
            echo "🔹";
        }      
            
        if ($likesCount)   //赞数
        {
            echo '『'.$likesCount.'』';
        }
        else
        {
            echo ' ';
        }   
            
        $name = $json->{'data'}[$i]->{'author'}->{'name'};
        if ($name == '知乎用户')
            echo '匿名';
        else
            echo $name;
        
        $reName = $json->{'data'}[$i]->{'inReplyToUser'}->{'name'};
            
        $isRe = ' ';         
       	if ($reName)
        {
            if ($reName == '知乎用户')
                $isRe =  '👉 匿名 :　';
            else
                $isRe =  "👉 ".$reName.' :　';
            
        }   
        
            echo '<br>'.$isRe.$json->{'data'}[$i]->{'content'}."<br><br>$divs<br>";
   	 	}
    }

	if ($json->{'paging'}->{'totalCount'} > 90)
    {
        $comlinkArr = 'http://www.zhihu.com/r/answers/'.$_GET['id'].'/comments?page=4';
    	$coment = file_get_contents($comlinkArr);
	
		$json = json_decode($coment);
        
        for ($i = 0 ; $i < ($json->{'paging'}->{'totalCount'} - 90) && $i < 30; $i++)
    	{
            
            // echo '<p>';
        
        $likesCount = $json->{'data'}[$i]->{'likesCount'};
        
        if ($i % 2 == 0)  //颜色
        {
            echo "🔸";
        }
        else
        {
            echo "🔹";
        }      
            
        if ($likesCount)   //赞数
        {
            echo '『'.$likesCount.'』';
        }
        else
        {
            echo ' ';
        }   
            
        $name = $json->{'data'}[$i]->{'author'}->{'name'};
        if ($name == '知乎用户')
            echo '匿名';
        else
            echo $name;
        
        $reName = $json->{'data'}[$i]->{'inReplyToUser'}->{'name'};
            
        $isRe = ' ';         
       	if ($reName)
        {
            if ($reName == '知乎用户')
                $isRe =  '👉 匿名 :　';
            else
                $isRe =  "👉 ".$reName.' :　';
            
        }   
        
            echo '<br>'.$isRe.$json->{'data'}[$i]->{'content'}."<br><br>$divs<br>";
   	 	}
    }

echo "</p>";

?>
