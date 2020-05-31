<?php
/**
 * WhoNews : index.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

include_once (__DIR__ . '/../../inc/reader.php');

$feedList = include(__DIR__ . '/../../inc/feedlist.php');

$limit = isset($_GET['limit']) ? $_GET['limit'] : 3;

$feedData = [];

$queryFeeds = [];
if (isset($_GET['feed'])) {
    $queryFeeds = array_filter($_GET['feed']);
}

if (empty($queryFeeds)) {
    $queryFeeds = [
	   'buzzfeed-news',
	   'nyt-homepage',
	   'foxnews-national'
    ];
}

foreach ($queryFeeds as $url) {
    $title = null;
    if (in_array($url, array_keys($feedList))) {
        $title = str_replace(' > ', '&nbsp; > &nbsp;', $feedList[$url]['title']);
        $url = $feedList[$url]['url'];
    }
    $feedData[] = getChannelData($url, $title, $limit);
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>WhoNews : MVP</title>

<link href="../css/bootstrap.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-theme.css" media="screen" rel="stylesheet" type="text/css" />
<link href="whonews-design.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="tablet-outer wn-cols-<?php echo count($feedData) ?>">

    <div class="wn-top-header">
<!--
        <div class="wn-header-burger">&#9776;</div>
        <div class="wn-header-beta">Beta</div>
-->
        <div class="wn-header-title">WhoNews</div>
    </div>

    <div class="wn-category-header">
        Pop Your Info Bubble!
    </div>

    <div class="wn-tabs clearfix">
        <?php
        foreach($feedData as $idx => $feed):
            $tabClass = 'wn-col wn-tab ' . ($idx === count($feedData) - 1 ? 'wn-tab-left' : '');
        ?>
            <div class="<?php echo $tabClass ?>">
                <?php echo $feed['title'] ?>
            </div>
        <?php
        endforeach;
        ?>
    </div>




  <div class="tablet-inner clearfix">


    <?php
    foreach($feedData as $idx => $feed):
        $wrapperClass = 'wn-col wn-links-wrapper ' . ($idx === count($feedData) - 1 ? 'wn-links-left' : '');
    ?>
        <div class="<?php echo $wrapperClass ?>">
        <?php
        foreach($feed['items'] as $item):
            $imgUrl = null;
            if (!empty($item['imgUrl'])) {
            	$imgUrl = $item['imgUrl'];
            } else if (!empty($item['thumbUrl'])) {
                $imgUrl = $item['thumbUrl'];
            }
        ?>
            <a href="<?php echo $item['guid'] ?>" class="">
                <?php
                if (!empty($imgUrl)):
                ?>
                <span class="wn-link-image">
                    <img src="<?php echo $imgUrl ?>" />
                </span>
                <?php
                endif;
                ?>
                <span class="wn-link-text">
    	            <span class="wn-link-title"><?php echo $item['title'] ?></span>
    	            <span class="wn-link-date"><?php echo $item['pubDate'] ?></span>
    	            <span class="wn-link-description"><?php echo $item['description'] ?></span>
                </span>
            </a>

        <?php
        endforeach;
        ?>
        </div><!-- .wn-links-wrapper -->

    <?php
    endforeach;
    ?>

  </div><!-- .tablet-inner -->
</div><!-- .tablet-outer -->

</body>
</html>