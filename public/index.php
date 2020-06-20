<?php
/**
 * WhoNews : index.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

include_once (__DIR__ . '/../inc/const.php');
include_once (__DIR__ . '/../inc/Feeds.php');
include_once (__DIR__ . '/../inc/Controller.php');
include_once (__DIR__ . '/../inc/Template.php');

$feedList = Feeds::$list;

$controller = new Controller();
$template = new Template();

$url = $controller->optimizeUrl($_GET);
if ($url !== null) {

    header("Location:?" . $url);
    exit;
}

$template->query = $_GET;
$template->queryFeeds = $controller->getQueryFeeds($_GET);

$limit = isset($_GET[WN_KEY_LIMIT]) ? $_GET[WN_KEY_LIMIT] : null;
$filter = isset($_GET[WN_KEY_FILTER]) ? $_GET[WN_KEY_FILTER] : null;

$template->feedData = $controller->getFeedData($template->queryFeeds, $limit, $filter);


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $template->getPageTitle(); ?></title>
<meta name="description" content="WhoNews.org is an online newsfeed viewer which allows users to compare multiple news sources by displaying them side-by-side. 
It currently offers <?php echo Feeds::countFeeds(); ?> feeds from <?php echo Feeds::countSources(); ?> sources. 
WhoNews follows no ideology or agenda; it is free, open-source, and does not use cookies, trackers, or ads of any kind.">

<link href="css/bootstrap.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-theme.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/whonews.css" media="screen" rel="stylesheet" type="text/css" />
<script src="js/functions.js" type="text/javascript"></script>
</head>
<body>

<div class="wn-tablet-outer <?php echo $template->getWrapperClass(); ?>">

    <div class="wn-top-header clearfix">
        <h1 class="wn-header-title">
            <span class="wn-header-title-img"></span>
            <span class="wn-header-title-text">WhoNews Beta</span>
        </h1>
        <div class="wn-sub-header-text">Pop Your Info Bubble</div>
        <div id="wn-settings-btn" title="Settings" onclick="toggleSettings()">
            <a href="#settings">Settings</a>
        </div>
    </div>

    <div class="wn-sub-header">
    </div>


    <div class="wn-tablet-inner clearfix">

        <nav class="wn-tabs clearfix">
        <?php
        foreach($template->feedData as $idx => $feed):
        ?>
            <div class="wn-tab wn-col wn-col-border <?php echo $template->getColumnClass($idx); ?>">

            <?php if (!empty($feed[WN_DATA_FEED_IMAGE])): ?>
              <div class="wn-tab-image" title="<?php echo $feed[WN_DATA_FEED_LABEL]; ?>">
                <span class="wn-img-bg" style="background-image: url(img/<?php echo $feed[WN_DATA_FEED_IMAGE]; ?>);"></span>
            <?php else: ?>
              <div class="wn-tab-text">
            <?php endif;?>
                <span>
                    <?php echo $feed[WN_DATA_FEED_LABEL]; ?>
                </span>
              </div>
              <ul>
                <li><a class="wn-column-anchor" href="#wn-column-<?php echo $idx; ?>">anchor</a></li>
                <li><a class="wn-link-rss" href="<?php echo $feed[WN_DATA_FEED_URL]; ?>" title="RSS Link" <?php echo $template->getTarget(); ?>><span>RSS Link</span></a></li>
              </ul>
            </div><!-- .wn-tab -->

        <?php
        endforeach;
        ?>
        </nav>

    <?php
    foreach($template->feedData as $idx => $feed):
    ?>

        <div class="wn-links-wrapper wn-col">

            <h2 class="wn-column-title">
                <a name="wn-column-<?php echo $idx; ?>">
                    <?php echo $feed[WN_DATA_FEED_LABEL]; ?>
                </a>
            </h2>

            <ul class="wn-col-border <?php echo $template->getColumnClass($idx); ?>">
            <?php
            foreach($feed[WN_DATA_FEED_ITEMS] as $item):
            ?>
                <li class="wn-item">

                <?php
                if ($imgUrl = $template->getItemImageUrl($item)):
                ?>
                    <div class="wn-item-image">
                        <img src="<?php echo $imgUrl ?>" />
                    </div>
                <?php
                endif;
                ?>
                    <div class="wn-item-text">
                      <div class="wn-item-title">
                        <a href="<?php echo $item[WN_DATA_ITEM_HREF]; ?>" class="wn-item-link" <?php echo $template->getTarget(); ?>>
                          <span><?php echo $item[WN_DATA_ITEM_TITLE] ?></span>
                        </a>
                      </div>

                    <?php
                    if (!empty($item[WN_DATA_ITEM_DESCRIPTION])):
                    ?>
                      <div class="wn-item-description">
                          <?php echo $template->formatDescription($item[WN_DATA_ITEM_DESCRIPTION]); ?>
                      </div>
                    <?php
                    endif;
                    ?>

                      <div class="wn-item-date">
                        <?php echo $feed[WN_DATA_FEED_TITLE]; ?>
                        <?php if (isset($item[WN_DATA_ITEM_PUB_DATE])): ?>
                            &nbsp;•&nbsp;&nbsp;<?php echo $item[WN_DATA_ITEM_PUB_DATE]; ?>
                        <?php endif; ?>
                      </div>

                    </div><!-- .wn-item-text -->
                </li>
            <?php
            endforeach;
            ?>
            </ul>
        </div><!-- .wn-links-wrapper -->

    <?php
    endforeach;
    ?>

    </div><!-- .tablet-inner -->


  <a name="settings" id="wn-settings-anchor"></a>
  <div id="wn-settings-panel" class="wn-settings-hidden">
    <form id="wn-settings-form" action="" method="get">
        <h3>DISPLAY OPTIONS</h3>

        <div class="wn-settings-row">
            <label>
                <span class="label_wide">Filter stories :</span>
                <input type=text name="<?php echo WN_KEY_FILTER; ?>" value="<?php echo $template->getFilterString(); ?>" tabindex="1" placeholder="Enter keyword" />
            </label>
        </div>

        <div class="wn-settings-row">
            <label>
                <span class="label_wide">Scroll feeds :</span>
                <select name="<?php echo WN_KEY_SCROLL; ?>" tabindex="1">
                    <option value="sync" <?php echo ($template->checkQuery(WN_KEY_SCROLL, WN_DEFAULT_SCROLL, true) ? 'selected="selected"' : ''); ?>>Together</option>
                    <option value="free" <?php echo ($template->checkQuery(WN_KEY_SCROLL, 'free') ? 'selected="selected"' : ''); ?>>Independently</option>
                </select>
            </label>
        </div>

        <div class="wn-settings-row">
            <label>
                <span class="label_wide">Images :</span>
                <select name="<?php echo WN_KEY_IMAGES; ?>" tabindex="1">
                    <option value="large" <?php echo ($template->checkQuery(WN_KEY_IMAGES, 'large') ? 'selected="selected"' : ''); ?>>Large</option>
                    <option value="small" <?php echo ($template->checkQuery(WN_KEY_IMAGES, WN_DEFAULT_IMAGES, true) ? 'selected="selected"' : ''); ?>>Small</option>
                    <option value="none"  <?php echo ($template->checkQuery(WN_KEY_IMAGES, 'none') ? 'selected="selected"' : ''); ?>>None</option>
                </select>
            </label>
        </div>

        <div class="wn-settings-row">
            <label>
                <span class="label_wide">Lede :</span>
                <select name="<?php echo WN_KEY_DESCRIPTION; ?>" tabindex="1">
                    <option value="full"  <?php echo ($template->checkQuery(WN_KEY_DESCRIPTION, 'full') ? 'selected="selected"' : ''); ?>>Full</option>
                    <option value="short" <?php echo ($template->checkQuery(WN_KEY_DESCRIPTION, 'short') ? 'selected="selected"' : ''); ?>>Short</option>
                    <option value="none"  <?php echo ($template->checkQuery(WN_KEY_DESCRIPTION, WN_DEFAULT_DESCRIPTION, true) ? 'selected="selected"' : ''); ?>>None</option>
                </select>
            </label>
        </div>

        <div class="wn-settings-row">
            <label>
                <span class="label_wide">Show :</span>
                <select name="<?php echo WN_KEY_LIMIT; ?>" tabindex="1">
                    <option value="1" <?php echo ($template->checkQuery(WN_KEY_LIMIT, '1') ? 'selected="selected"' : ''); ?>>Top story only</option>
                    <option value="5"  <?php echo ($template->checkQuery(WN_KEY_LIMIT, '5') ? 'selected="selected"' : ''); ?>>First 5 stories</option>
                    <option value="10"  <?php echo ($template->checkQuery(WN_KEY_LIMIT, '10') ? 'selected="selected"' : ''); ?>>First 10 stories</option>
                    <option value="20"  <?php echo ($template->checkQuery(WN_KEY_LIMIT, '20') ? 'selected="selected"' : ''); ?>>First 20 stories</option>
                    <option value=""  <?php echo ($template->checkQuery(WN_KEY_LIMIT, '', true) ? 'selected="selected"' : ''); ?>>All stories</option>
                    </select>
            </label>
        </div>

        <div class="wn-settings-row">
            <label>
                <span class="label_wide">Open links in :</span>
                <select name="<?php echo WN_KEY_TARGET; ?>" tabindex="1">
                    <option value="new"  <?php echo ($template->checkQuery(WN_KEY_TARGET, WN_DEFAULT_TARGET, true) ? 'selected="selected"' : ''); ?>>New tab</option>
                    <option value="same" <?php echo ($template->checkQuery(WN_KEY_TARGET, 'same') ? 'selected="selected"' : ''); ?>>Current tab</option>
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
                    <select name="<?php echo sprintf('%s[%d]', WN_KEY_FEED, $i); ?>" onchange="toggleCustomInput('wn-input-custom-<?php echo $i; ?>', this.options[this.selectedIndex].value)" tabindex="2">
                        <option value=""></option>
                        <option value="custom" <?php echo ($template->displayCustom($i) ? 'selected="selected"' : '');?>>Custom...</option>
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
                <label class="wn-input-custom <?php echo (!$template->displayCustom($i) ? 'wn-custom-hidden' : '');?>" id="wn-input-custom-<?php echo $i; ?>">
                    <span>
                        RSS URL :&nbsp;
                        <input type="text" name="<?php echo sprintf('%s[%d]', WN_KEY_CUSTOM, $i); ?>" size="40" value="<?php echo (!isset($feedList[$feed]) ? $feed : ''); ?>" />
                    </span>
                </label>
            </div>
        <?php
        endfor;
        ?>


        <div class="wn-settings-row text-center">
            <button id="wn-settings-submit-btn" type="submit" class="btn btn-success btn-sm" title="Apply" tabindex="3">Apply</button>
        </div>

    </form>
  </div><!-- #wn-settings-panel -->


</div><!-- .tablet-outer -->

</body>
</html>