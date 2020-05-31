<?php
/**
 * WhoNews : index.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

include_once (__DIR__ . '/../../inc/reader.php');

$feedData = [];
foreach ($_GET['feed'] as $url) {
    $feedData[] = getChannelData($url, 2);
}
if (empty($feedData)) {
    $feedData[] = getChannelData('https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml', 2);
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
            $tabClass = 'wn-tab ' . ($idx === count($feedData) - 1 ? 'wn-tab-left' : '');
        ?>
            <div class="<?php echo $tabClass ?>">
                <?php echo $feed['title'] ?>
            </div>
        <?php
        endforeach;
        ?>
    </div>




  <div class="tablet-inner">


    <?php
    foreach($feedData as $idx => $feed):
        $wrapperClass = 'wn-links-wrapper ' . ($idx === count($feedData) - 1 ? 'wn-links-left' : '');
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

    <div class="clearfix"></div>

  </div><!-- .tablet-inner -->
</div><!-- .tablet-outer -->

</body>
</html>