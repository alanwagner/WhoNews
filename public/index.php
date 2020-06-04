<?php
/**
 * WhoNews : index.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

include_once (__DIR__ . '/../inc/const.php');

include_once (__DIR__ . '/../inc/url.php');

include_once (__DIR__ . '/../inc/template.php');

include_once (__DIR__ . '/../inc/reader.php');


$feedList = include(__DIR__ . '/../inc/feedlist.php');

$limit = isset($_GET['limit']) ? $_GET['limit'] : null;

$queryFeeds = getQueryFeeds();

$feedData = getFeedData($queryFeeds, $limit);

$pageTitle = getPageTitle($feedData);

$wrapperClass = getWrapperClass(count($feedData));

$shortDescription = (boolean)strstr($wrapperClass, ' wn-description-short');

$targetNew = (isset($_GET[WN_KEY_TARGET]) && $_GET[WN_KEY_TARGET] === 'new');


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $pageTitle; ?></title>
<meta name="description" content="WhoNews.org is an online newsfeed viewer which allows users to compare multiple news sources by displaying them side-by-side. It is free, open-source, and does not use cookies or trackers of any kind.">

<link href="css/bootstrap.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-theme.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/whonews.css" media="screen" rel="stylesheet" type="text/css" />
<script src="js/functions.js" type="text/javascript"></script>
</head>
<body>

<div class="<?php echo $wrapperClass; ?>">

    <div class="wn-top-header">
        <h1 class="wn-header-title">Who<span class="wn-header-title-spacer"></span>News</h1>
    </div>

    <div class="wn-sub-header">
        <span class="wn-sub-header-text">Pop Your Info Bubble</span>
        <span title="Settings" class="wn-settings-btn" onclick="toggleSettings()">&nbsp;</span>
    </div>

    <div class="wn-tabs clearfix">
        <?php
        foreach($feedData as $idx => $feed):
        ?>
            <h2 class="wn-col wn-tab">
                <?php echo str_replace(' > ', '&nbsp; > &nbsp;', $feed[WN_DATA_FEED_TITLE]); ?>
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
        foreach($feed[WN_DATA_FEED_ITEMS] as $item):
            $imgUrl = null;
            if (!empty($item[WN_DATA_ITEM_IMAGE_URL])) {
                $imgUrl = $item[WN_DATA_ITEM_IMAGE_URL];
            } else if (!empty($item[WN_DATA_ITEM_THUMB_URL])) {
                $imgUrl = $item[WN_DATA_ITEM_THUMB_URL];
            }
        ?>
            <a href="<?php echo $item[WN_DATA_ITEM_GUID] ?>" class="wn-link-block" <?php echo ($targetNew) ? 'target="_blank"' : '';?>>
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
                        $descr = $item[WN_DATA_ITEM_DESCRIPTION];
                        if ($shortDescription === true && strlen($descr) > 120):
                            $descr = substr($descr, 0, strrpos(substr($descr, 0, 120), ' ')) . '...';
                        endif;
                    ?>
                        <span class="wn-link-description"><?php echo $descr; ?></span>
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


    <div id="wn-settings-wrapper" class="wn-settings-hidden">
        <form id="wn-settings-form" action="" method="get">
            <h3>DISPLAY OPTIONS</h3>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Scrolling :</span>
                    <select name="<?php echo WN_KEY_SCROLL; ?>" tabindex="1">
                        <option value="free" <?php echo (checkGet(WN_KEY_SCROLL, WN_DEFAULT_SCROLL, true) ? 'selected="selected"' : ''); ?>>Free</option>
                        <option value="sync" <?php echo (checkGet(WN_KEY_SCROLL, 'sync') ? 'selected="selected"' : ''); ?>>Sync</option>
                    </select>
                </label>
            </div>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Images :</span>
                    <select name="<?php echo WN_KEY_IMAGES; ?>" tabindex="2">
                        <option value="large" <?php echo (checkGet(WN_KEY_IMAGES, 'large') ? 'selected="selected"' : ''); ?>>Large</option>
                        <option value="small" <?php echo (checkGet(WN_KEY_IMAGES, WN_DEFAULT_IMAGES, true) ? 'selected="selected"' : ''); ?>>Small</option>
                        <option value="none"  <?php echo (checkGet(WN_KEY_IMAGES, 'none') ? 'selected="selected"' : ''); ?>>None</option>
                    </select>
                </label>
            </div>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Description :</span>
                    <select name="<?php echo WN_KEY_DESCRIPTION; ?>" tabindex="3">
                        <option value="full"  <?php echo (checkGet(WN_KEY_DESCRIPTION, 'full') ? 'selected="selected"' : ''); ?>>Full</option>
                        <option value="short" <?php echo (checkGet(WN_KEY_DESCRIPTION, 'short') ? 'selected="selected"' : ''); ?>>Short</option>
                        <option value="none"  <?php echo (checkGet(WN_KEY_DESCRIPTION, WN_DEFAULT_DESCRIPTION, true) ? 'selected="selected"' : ''); ?>>None</option>
                    </select>
                </label>
            </div>

            <div class="wn-settings-row">
                <label>
                    <span class="label_wide">Open links in :</span>
                    <select name="<?php echo WN_KEY_TARGET; ?>" tabindex="4">
                        <option value="same" <?php echo (checkGet(WN_KEY_TARGET, WN_DEFAULT_TARGET, true) ? 'selected="selected"' : ''); ?>>Current tab</option>
                        <option value="new"  <?php echo (checkGet(WN_KEY_TARGET, 'new') ? 'selected="selected"' : ''); ?>>New tab</option>
                    </select>
                </label>
            </div>


            <h3>FEEDS</h3>

            <?php
            for ($i = 0; $i < WN_MAX_FEEDS; $i++):
                $feed = isset($queryFeeds[$i]) ? $queryFeeds[$i] : null;
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
    </div><!-- #wn-settings-wrapper -->


</div><!-- .tablet-outer -->

</body>
</html>