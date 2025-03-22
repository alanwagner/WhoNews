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

$controller = new Controller();
$template = new Template();

$url = $controller->optimizeUrl($_GET);
if ($url !== null) {

    header("Location:?" . $url);
    exit;
}

$template->query = $_GET;
$template->queryFeeds = $controller->getQueryFeeds($_GET);

$defaultTitle = 'WhoNews.org | Compare RSS news feeds side by side';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $defaultTitle; ?></title>
<meta name="description" content="WhoNews.org is an online newsfeed viewer which allows users to compare multiple news sources by displaying them side by side. 
It currently offers <?php echo Feeds::countFeeds(); ?> feeds from <?php echo Feeds::countSources(); ?> sources. 
WhoNews follows no ideology or agenda; it is free, open-source, and does not use cookies, trackers, or ads of any kind.
WhoNews exists in its current form purely as a personal project created by Alan Gerard Wagner (see alanwagner.org).">

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
    <?php
    $filterString = $template->getFilterString();
    if (!empty($filterString)):
    ?>
        <div class="wn-keywords"><span class="wn-keywords-label">Keyword<?php echo strstr($filterString, ' ') ? 's' : ''; ?>: </span><?php echo $filterString; ?></div>
    <?php
    endif;
    ?>
        <div id="wn-settings-btn" title="Settings" onclick="toggleSettings()">
            <a href="#settings">Settings</a>
        </div>
    </div>

    <div class="wn-sub-header">
    </div>


    <div class="wn-tablet-inner clearfix">

        <nav class="wn-tabs clearfix">
        <?php
        foreach($template->queryFeeds as $idx => $feed):
        ?>
            <div id="wn-tab-<?php echo $idx ?>" class="wn-tab wn-col wn-col-border <?php echo $template->getColumnClass($idx); ?>">
            </div><!-- .wn-tab -->

        <?php
        endforeach;
        ?>
        </nav>

    <?php
    foreach($template->queryFeeds as $idx => $feed):
    ?>

        <div class="wn-links-wrapper wn-col">

            <ul id="wn-col-<?php echo $idx ?>" class="wn-col-border <?php echo $template->getColumnClass($idx); ?>">
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
                <input type=text name="<?php echo WN_KEY_FILTER; ?>" value="<?php echo $template->getFilterString(); ?>" tabindex="1" placeholder="Enter keywords" />
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
                        <option value="custom" <?php echo ($template->displayCustom($i) === true ? 'selected="selected"' : '');?>>Custom...</option>
                        <?php
                        foreach (Feeds::getAllFeeds() as $key => $conf):
                        ?>
                            <option value="<?php echo $key; ?>" <?php echo ($feed === $key ? 'selected="selected"' : '');?>>
                                <?php echo Template::formatFeedLabel($conf); ?>
                            </option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </label>
                <br />
                <label class="wn-input-custom <?php echo ($template->displayCustom($i) === false ? 'wn-custom-hidden' : '');?>" id="wn-input-custom-<?php echo $i; ?>">
                    <span>
                        RSS URL :&nbsp;
                        <input type="text" name="<?php echo sprintf('%s[%d]', WN_KEY_CUSTOM, $i); ?>" size="40" value="<?php echo (Feeds::isCustomFeed($feed) ? $feed : ''); ?>" />
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


<script>

<?php
$baseQuery = $template->query;
$urls = [];
foreach ($template->queryFeeds as $feed) {
    $baseQuery[WN_KEY_FEED] = $feed;
    $urls[] = 'data.php?' . http_build_query($baseQuery);
}
?>
    const defaultPageTitle = '<?php 
        if (empty($template->query[WN_KEY_FEED])) {
            echo $defaultTitle;
        }
    ?>';

    const urls = [
        '<?php echo implode("',\n'", $urls) ?>'
    ];

    urls.map((url, idx) => { loadFeed(url, idx) });

</script>

</body>
</html>