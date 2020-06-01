<?php
/**
 * WhoNews : index.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

include_once (__DIR__ . '/../inc/reader.php');

$feedList = include(__DIR__ . '/../inc/feedlist.php');

$limit = isset($_GET['limit']) ? $_GET['limit'] : null;

$feedData = [];
$queryFeeds = [];
$defaultTitle = false;

if (isset($_GET['feed'])) {
    $queryFeeds = array_merge(array_filter($_GET['feed']));
}

if (empty($queryFeeds)) {
    //  Use default values if no feeds read
    $queryFeeds = [
       'buzzfeed-news',
       'nyt-homepage',
       'foxnews-national'
    ];
    $defaultTitle = true;
}


foreach ($queryFeeds as $idx => $url) {

    $title = null;
    if (in_array($url, array_keys($feedList))) {
        $title = $feedList[$url]['title'];
        $url = $feedList[$url]['url'];
    }

    $feedData[] = getChannelData($url, $title, $limit);
}

$pageTitle = 'WhoNews :: ';

if ($defaultTitle === false) {
    foreach ($feedData as $idx => $feed) {
        $pageTitle .= $feed['title'];
        if ($idx !== count($queryFeeds) - 1) {
            $pageTitle .= ' • ';
        }
    }
} else {
    $pageTitle .= 'Pop Your Info Bubble';
}

$wrapperClass = 'wn-tablet-outer wn-cols-' . count($feedData);
if (isset($_GET['scroll']) && $_GET['scroll'] === 'sync') {
    //  Default is scroll-free
    $wrapperClass .= ' wn-scroll-sync';
}
if (isset($_GET['images']) && $_GET['images'] === 'hide') {
    //  Default is images-show
    $wrapperClass .= ' wn-images-hide';
}
$shortDescription = false;
if (isset($_GET['description'])) {
    //  Default is description-none
    switch ($_GET['description']) {
        case 'full':
            $wrapperClass .= ' wn-description-full';
            break;
        case 'short':
            $wrapperClass .= ' wn-description-short';
            $shortDescription = true;
            break;
    }
}


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $pageTitle; ?></title>

<link href="css/bootstrap.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-theme.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/whonews.css" media="screen" rel="stylesheet" type="text/css" />
<script src="js/functions.js" type="text/javascript"></script>
</head>
<body>

<div class="<?php echo $wrapperClass; ?>">

    <div class="wn-top-header">
<!--
        <div class="wn-header-burger">&#9776;</div>
        <div class="wn-header-beta">Beta</div>
-->
        <h1 class="wn-header-title">WhoNews</h1>
    </div>

    <div class="wn-sub-header">
        <span class="wn-sub-header-text">Pop Your Info Bubble!</span>
        <span title="Settings" class="wn-settings-btn" onclick="toggleSettings()">&#x2699;</span>
    </div>

    <div class="wn-tabs clearfix">
        <?php
        foreach($feedData as $idx => $feed):
        ?>
            <h2 class="wn-col wn-tab">
                <?php echo str_replace(' > ', '&nbsp; > &nbsp;', $feed['title']); ?>
            </h2>
        <?php
        endforeach;
        ?>
    </div>




  <div class="wn-tablet-inner clearfix">


    <?php
    foreach($feedData as $idx => $feed):
    ?>
        <div class="wn-col wn-links-wrapper">
        <?php
        foreach($feed['items'] as $item):
            $imgUrl = null;
            if (!empty($item['imgUrl'])) {
                $imgUrl = $item['imgUrl'];
            } else if (!empty($item['thumbUrl'])) {
                $imgUrl = $item['thumbUrl'];
            }
        ?>
            <a href="<?php echo $item['guid'] ?>" class="wn-link-block">
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
                    <span class="wn-link-date">
                        <?php echo $feed['title'] ?>
                        <br />
                        <?php echo $item['pubDate'] ?>
                    </span>
                <?php
                if (!empty($item['description'])):
                    $descr = $item['description'];
                    if ($shortDescription === true && strlen($descr) > 120):
                        $descr = substr($descr, 0, strrpos(substr($descr, 0, 120), ' ')) . '...';
                    endif;
                ?>
                    <span class="wn-link-description"><?php echo $descr; ?></span>
                <?php
                endif;
                ?>
                </span><!-- .wn-link-text -->
            </a>

        <?php
        endforeach;
        ?>
        </div><!-- .wn-links-wrapper -->

    <?php
    endforeach;
    ?>

  </div><!-- .tablet-inner -->


    <div id="wn-settings-wrapper" class="wn-settings-hidden">
        <form id="wn-settings-form" action="" method="get">
            <h3>SETTINGS</h3>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Scrolling :</span>
                    <select name="scroll">
                        <option value="free" <?php echo (!isset($_GET['scroll']) || $_GET['scroll'] === 'free' ? 'selected="selected"' : '');?>>Free</option>
                        <option value="sync" <?php echo (isset($_GET['scroll']) && $_GET['scroll'] === 'sync' ? 'selected="selected"' : '');?>>Sync</option>
                    </select>
                </label>
            </div>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Images :</span>
                    <select name="images">
                        <option value="show" <?php echo (!isset($_GET['images']) || $_GET['images'] === 'show' ? 'selected="selected"' : '');?>>Show</option>
                        <option value="hide" <?php echo (isset($_GET['images']) && $_GET['images'] === 'hide' ? 'selected="selected"' : '');?>>Hide</option>
                    </select>
                </label>
            </div>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Description :</span>
                    <select name="description">
                        <option value="full" <?php echo (isset($_GET['description']) && $_GET['description'] === 'full' ? 'selected="selected"' : '');?>>Full</option>
                        <option value="short" <?php echo (isset($_GET['description']) && $_GET['description'] === 'short' ? 'selected="selected"' : '');?>>Short</option>
                        <option value="none" <?php echo (!isset($_GET['description']) || $_GET['description'] === 'none' ? 'selected="selected"' : '');?>>None</option>
                    </select>
                </label>
            </div>


            <h3>FEEDS</h3>

            <?php
            for ($i = 1; $i <= 5; $i++):
                $feed = isset($queryFeeds[$i-1]) ? $queryFeeds[$i-1] : null;
            ?>
                <div class="wn-settings-row">
                    <label>
                        <span><?php echo $i; ?> :&nbsp;</span>
                        <select name="feed[]">
                            <option value=""></option>
                            <option value="custom" <?php echo ($feed !== null && !isset($feedList[$feed]) ? 'selected="selected"' : '');?>>Custom...</option>
                            <?php
                            foreach ($feedList as $key => $conf):
                            ?>
                                <option value="<?php echo $key; ?>" <?php echo ($feed === $key ? 'selected="selected"' : '');?>>
                                    <?php echo $conf['menuLabel']?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </label>
                </div>
            <?php
            endfor;
            ?>


            <div class="wn-settings-row text-center">
                <button type="submit" class="btn btn-success btn-sm">Save</button>
            </div>

        </form>
    </div><!-- #wn-settings-wrapper -->


</div><!-- .tablet-outer -->

</body>
</html>