<?php
/**
 * WhoNews : data.php
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

$target = $template->getTarget();
$feedTitle = Template::formatFeedTitle($feed);

$tabData = [
    'label' => Template::formatFeedLabel($feed),
    'image' => !empty($feed[Feeds::SOURCE_IMAGE]) ? $feed[Feeds::SOURCE_IMAGE] : false,
    'url' => $feed[Feeds::FEED_URL],
    'target' => $target,
];

$columnData = [];
foreach ($feed[WN_DATA_FEED_ITEMS] as $item) {
    $imgUrl = $template->getItemImageUrl($item);
    $columnData[] = [
        'title' => Template::clean($item[WN_DATA_ITEM_TITLE]),
        'description' => !empty($item[WN_DATA_ITEM_DESCRIPTION]) ? $template->formatDescription($item[WN_DATA_ITEM_DESCRIPTION]) : false,
        'date' => $item[WN_DATA_ITEM_PUB_DATE] ?? false,
        'itemUrl' => $item[WN_DATA_ITEM_HREF],
        'imageUrl' => !empty($imgUrl) ? $imgUrl : false,
        'feedTitle' => $feedTitle,
        'target' => $target,
    ];
}

$output = [
    "tabData" => $tabData,
    "columnData" => $columnData,
];

header('Content-Type: application/json; charset=utf-8');

echo json_encode($output);