<?php
// Require
require('RSS-master/vendor/CyrilPerrin/Rss/Rss.php');
// HTTP header
header('content-type:application/rss+xml; ');
/*
$which = $_GET["id"];

use sinacloud\sae\Storage as Storage;
$s = new Storage();
$s->putObject($which, "kpr", "div.txt", array(), array('Content-Type' => 'text/txt'));

*/
$pubTime = $_SERVER['REQUEST_TIME'];
$rss = new CyrilPerrin\Rss\Rss(
    $which, 'https://www.zhihu.com', 'div', 'zh', '120', $pubTime
);

$rss->addItem(
    0, 'DOTA2 MMR', 'http://1.jiese.applinzi.com/RSS-master/example/dota2.php', '', 'dota2',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', $linkModel[$i]
);

$rss->addItem(
    0, '天气', 'http://1.jiese.applinzi.com/weather.php', '', 'weather',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', $linkModel[$i]
);


$rss->addItem(
    0, '地图', 'http://1.jiese.applinzi.com/map.php?beg='.$_GET['beg'].'kpr'.$_GET['end'], '', 'map',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', $linkModel[$i]
);


$rss->addItem(
    0, '打卡', 'http://1.jiese.applinzi.com/game/rpg.php', '', 'sign',
    'Cyril', $_SERVER['REQUEST_TIME'], 'Comments', $linkModel[$i]
);

	$rss->addItem(
        1, '设置为小', 'http://1.jiese.applinzi.com/divPageS.php', '', 'Zhihu',
    'Cyril', $pubTime, 'Comments', 'https://www.zhihu.com');

	$rss->addItem(
        1, '设置为中', 'http://1.jiese.applinzi.com/divPageM.php', '', 'Zhihu',
    'Cyril', $pubTime, 'Comments', 'https://www.zhihu.com');

	$rss->addItem(
        1, '设置为大', 'http://1.jiese.applinzi.com/divPageL.php', '', 'Zhihu',
    'Cyril', $pubTime, 'Comments', 'https://www.zhihu.com');
echo $rss;

?>
