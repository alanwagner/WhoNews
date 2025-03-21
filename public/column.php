<?php
/**
 * WhoNews : column.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2025 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

include_once (__DIR__ . '/../inc/const.php');
include_once (__DIR__ . '/../inc/Feeds.php');
include_once (__DIR__ . '/../inc/Controller.php');
include_once (__DIR__ . '/../inc/Template.php');

$controller = new Controller();
$template = new Template();

$template->query = $_GET;
$template->queryFeeds = $controller->getQueryFeeds($_GET);

$limit = isset($_GET[WN_KEY_LIMIT]) ? $_GET[WN_KEY_LIMIT] : null;
$filter = isset($_GET[WN_KEY_FILTER]) ? $_GET[WN_KEY_FILTER] : null;

$template->feedData = $controller->getFeedData($template->queryFeeds, $limit, $filter);
$feed = $template->feedData[0];

header('Content-Type: application/json; charset=utf-8');
?>
{
  "tabHtml": "<?php
    if (!empty($feed[Feeds::SOURCE_IMAGE])):
        ?><div class=\"wn-tab-image\" title=\"<?php
            echo Template::formatFeedLabel($feed, true);
        ?>\"><span class=\"wn-img-bg\" style=\"background-image: url(img/<?php
            echo $feed[Feeds::SOURCE_IMAGE];
        ?>);\"></span><?php
    else:
        ?><div class=\"wn-tab-text\"><?php
    endif;
    ?><span class=\"wn-tab-label\"><?php
        echo Template::formatFeedLabel($feed, true);
    ?></span></div><ul><li><a class=\"wn-link-rss\" href=\"<?php
                    echo $feed[Feeds::FEED_URL];
                ?>\" title=\"RSS Link\" <?php echo $template->getTarget(true);
    ?>><span>RSS Link</span></a></li></ul>",

  "columnHtml": "<?php
    foreach($feed[WN_DATA_FEED_ITEMS] as $item):
        ?><li class=\"wn-item\"><?php
        if ($imgUrl = $template->getItemImageUrl($item)):
            ?><div class=\"wn-item-image\"><img src=\"<?php echo $imgUrl ?>\" /></div><?php
        endif;
        ?><div class=\"wn-item-text\"><div class=\"wn-item-title\"><a href=\"<?php echo $item[WN_DATA_ITEM_HREF]; ?>\" class=\"wn-item-link\" <?php echo $template->getTarget(true); ?>><span><?php
            echo Template::escape(str_replace(chr(194) . chr(160), ' ', $item[WN_DATA_ITEM_TITLE]))
        ?></span></a></div><?php
            if (!empty($item[WN_DATA_ITEM_DESCRIPTION])):
                ?><div class=\"wn-item-description\"><?php
                    echo $template->formatDescription($item[WN_DATA_ITEM_DESCRIPTION], true);
                ?></div><?php
            endif;
            ?><div class=\"wn-item-date\"><?php
                echo Template::formatFeedTitle($feed, true);
                if (isset($item[WN_DATA_ITEM_PUB_DATE])):
                    ?>&nbsp;•&nbsp;&nbsp;<?php echo $item[WN_DATA_ITEM_PUB_DATE];
                endif; ?></div></div></li><?php
    endforeach;
    ?>"
}