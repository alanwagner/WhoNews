<?php
/**
 * WhoNews : index.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

include_once (__DIR__ . '/../inc/const.php');

include_once (__DIR__ . '/../inc/Controller.php');

include_once (__DIR__ . '/../inc/Template.php');


$feedList = include(__DIR__ . '/../inc/feedlist.php');

$controller = new Controller();
$template = new Template();

$url = $controller->optimizeUrl($_GET);
if ($url !== null) {

    header("Location:?" . $url);
    exit;
}

$template->query = $_GET;
$template->queryFeeds = $controller->getQueryFeeds($_GET);

$limit = isset($_GET['limit']) ? $_GET['limit'] : null;
$template->feedData = $controller->getFeedData($template->queryFeeds, $limit);


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $template->getPageTitle(); ?></title>
<meta name="description" content="WhoNews.org is an online newsfeed viewer which allows users to compare multiple news sources by displaying them side-by-side. WhoNews follows no ideology or agenda; it is free, open-source, and does not use cookies or trackers of any kind.">

<link href="css/bootstrap.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-theme.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/whonews.css" media="screen" rel="stylesheet" type="text/css" />
<script src="js/functions.js" type="text/javascript"></script>
</head>
<body>

<div class="<?php echo $template->getWrapperClass(); ?>">

    <div class="wn-top-header clearfix">
        <h1 class="wn-header-title">Who<span class="wn-header-title-spacer"></span>News</h1>
        <div class="wn-sub-header-text">Pop Your Info Bubble</div>
        <a title="Settings" class="wn-settings-btn" onclick="toggleSettings()" href="#settings">
            <span>Settings</span>
        </a>
    </div>

    <div class="wn-sub-header">
    </div>

    <div class="wn-tabs clearfix">
        <?php
        foreach($template->feedData as $idx => $feed):
            $tabClass = 'wn-col wn-tab';
            if ($idx === 0) {
                $tabClass .= ' wn-col-left';
            }
            if ($idx === count($template->feedData) -1) {
                $tabClass .= ' wn-col-right';
            }
        ?>
            <?php if (!empty($feed[WN_DATA_FEED_IMAGE])): ?>
                <h2 class="<?php echo $tabClass; ?> wn-tab-image" title="<?php echo $feed[WN_DATA_FEED_TITLE]; ?>">
                    <span class="wn-img-bg" style="background-image: url(img/<?php echo $feed[WN_DATA_FEED_IMAGE]; ?>);"></span>
            <?php else: ?>
                <h2 class="<?php echo $tabClass; ?>">
            <?php endif;?>
                    <span>
                        <?php echo str_replace(' > ', '&nbsp; > &nbsp;', $feed[WN_DATA_FEED_TITLE]); ?>
                    </span>
                </h2>
        <?php
        endforeach;
        ?>
    </div>




  <div class="wn-tablet-inner clearfix">


    <?php
    foreach($template->feedData as $idx => $feed):
        $colClass = 'wn-col wn-links-wrapper';
        if ($idx === 0) {
            $colClass .= ' wn-col-left';
        }
        if ($idx === count($template->feedData) -1) {
            $colClass .= ' wn-col-right';
        }
    ?>
        <div class="<?php echo $colClass; ?>">
        <?php
        foreach($feed[WN_DATA_FEED_ITEMS] as $item):
            $imgUrl = null;
            if (!empty($item[WN_DATA_ITEM_IMAGE_URL])) {
                $imgUrl = $item[WN_DATA_ITEM_IMAGE_URL];
            } else if (!empty($item[WN_DATA_ITEM_THUMB_URL])) {
                $imgUrl = $item[WN_DATA_ITEM_THUMB_URL];
            }
        ?>
            <a href="<?php echo $item[WN_DATA_ITEM_HREF]; ?>" class="wn-link-block" <?php echo $template->getTarget(); ?>>
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
                    <span class="wn-link-title"><?php echo $item[WN_DATA_ITEM_TITLE] ?></span>
                    <?php
                    if (!empty($item[WN_DATA_ITEM_DESCRIPTION])):
                    ?>
                        <span class="wn-link-description"><?php echo $template->formatDescription($item[WN_DATA_ITEM_DESCRIPTION]); ?></span>
                    <?php
                    endif;
                    ?>
                    <span class="wn-link-date">
                        <?php echo $feed[WN_DATA_FEED_TITLE] ?>
                        <?php if (isset($item[WN_DATA_ITEM_PUB_DATE])): ?>
                            &nbsp;•&nbsp;&nbsp;<?php echo $item[WN_DATA_ITEM_PUB_DATE]; ?>
                        <?php endif; ?>
                    </span>
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


  <div id="wn-settings-wrapper">
    <a name="settings"></a>
    <div id="wn-settings-panel" class="wn-settings-hidden">
        <form id="wn-settings-form" action="" method="get">
            <h3>DISPLAY OPTIONS</h3>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Scrolling :</span>
                    <select name="<?php echo WN_KEY_SCROLL; ?>" tabindex="1">
                        <option value="free" <?php echo ($template->checkQuery(WN_KEY_SCROLL, WN_DEFAULT_SCROLL, true) ? 'selected="selected"' : ''); ?>>Free</option>
                        <option value="sync" <?php echo ($template->checkQuery(WN_KEY_SCROLL, 'sync') ? 'selected="selected"' : ''); ?>>Sync</option>
                    </select>
                </label>
            </div>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Images :</span>
                    <select name="<?php echo WN_KEY_IMAGES; ?>" tabindex="2">
                        <option value="large" <?php echo ($template->checkQuery(WN_KEY_IMAGES, 'large') ? 'selected="selected"' : ''); ?>>Large</option>
                        <option value="small" <?php echo ($template->checkQuery(WN_KEY_IMAGES, WN_DEFAULT_IMAGES, true) ? 'selected="selected"' : ''); ?>>Small</option>
                        <option value="none"  <?php echo ($template->checkQuery(WN_KEY_IMAGES, 'none') ? 'selected="selected"' : ''); ?>>None</option>
                    </select>
                </label>
            </div>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Description :</span>
                    <select name="<?php echo WN_KEY_DESCRIPTION; ?>" tabindex="3">
                        <option value="full"  <?php echo ($template->checkQuery(WN_KEY_DESCRIPTION, 'full') ? 'selected="selected"' : ''); ?>>Full</option>
                        <option value="short" <?php echo ($template->checkQuery(WN_KEY_DESCRIPTION, 'short') ? 'selected="selected"' : ''); ?>>Short</option>
                        <option value="none"  <?php echo ($template->checkQuery(WN_KEY_DESCRIPTION, WN_DEFAULT_DESCRIPTION, true) ? 'selected="selected"' : ''); ?>>None</option>
                    </select>
                </label>
            </div>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Open links in :</span>
                    <select name="<?php echo WN_KEY_TARGET; ?>" tabindex="4">
                        <option value="same" <?php echo ($template->checkQuery(WN_KEY_TARGET, WN_DEFAULT_TARGET, true) ? 'selected="selected"' : ''); ?>>Current tab</option>
                        <option value="new"  <?php echo ($template->checkQuery(WN_KEY_TARGET, 'new') ? 'selected="selected"' : ''); ?>>New tab</option>
                    </select>
                </label>
            </div>


            <h3>FEEDS</h3>

            <?php
            for ($i = 0; $i < WN_MAX_FEEDS; $i++):
                $feed = isset($template->queryFeeds[$i]) ? $template->queryFeeds[$i] : null;
            ?>
                <div class="wn-settings-row">
                    <label>
                        <span><?php echo ($i+1); ?> :&nbsp;</span>
                        <select name="<?php echo sprintf('%s[%d]', WN_KEY_FEED, $i); ?>" onchange="toggleCustomInput('wn-input-custom-<?php echo $i; ?>', this.options[this.selectedIndex].value)" tabindex="<?php echo($i+5); ?>">
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
                    <br />
                    <label class="wn-input-custom <?php echo ($feed === null || isset($feedList[$feed]) ? 'wn-custom-hidden' : '');?>" id="wn-input-custom-<?php echo $i; ?>">
                        RSS URL :&nbsp;
                        <input type="text" name="<?php echo sprintf('%s[%d]', WN_KEY_CUSTOM, $i); ?>" size="40" value="<?php echo (!isset($feedList[$feed]) ? $feed : ''); ?>" />
                    </label>
                </div>
            <?php
            endfor;
            ?>


            <div class="wn-settings-row text-center">
                <button type="submit" class="btn btn-success btn-sm" title="Apply" tabindex="<?php echo(WN_MAX_FEEDS + 5); ?>">Apply</button>
            </div>

        </form>
    </div><!-- #wn-settings-panel -->
  </div><!-- #wn-settings-wrapper -->


</div><!-- .tablet-outer -->

</body>
</html>