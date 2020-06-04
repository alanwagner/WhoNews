<?php
/**
 * WhoNews : template.php
 *
 * Methods and processing used in the front template
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */


function getQueryFeeds()
{
    $queryFeeds = [
        'buzzfeed-news',
        'nyt-homepage',
        'foxnews-national',
    ];

    if (isset($_GET[WN_KEY_FEED])) {
        $queryFeeds = $_GET[WN_KEY_FEED];
    }

    return $queryFeeds;
}

function getFeedData($queryFeeds, $limit)
{
    $feedList = include(__DIR__ . '/feedlist.php');

    $feedData = [];
    foreach ($queryFeeds as $idx => $url) {
        $title = null;
        if (in_array($url, array_keys($feedList))) {
            $title = $feedList[$url]['title'];
            $url = $feedList[$url]['url'];
        }

        $feedData[] = getChannelData($url, $title, $limit);
    }

    return $feedData;
}

function getPageTitle($feedData)
{
    $pageTitle = 'WhoNews :: ';

    if (isset($_GET[WN_KEY_FEED])) {
        foreach ($feedData as $idx => $feed) {
            $pageTitle .= $feed[WN_DATA_FEED_TITLE];
            if ($idx !== count($feedData) - 1) {
                $pageTitle .= ' • ';
            }
        }
    } else {
        $pageTitle .= 'Pop Your Info Bubble';
    }
}

function getWrapperClass($feedCount)
{
    $wrapperClass = 'wn-tablet-outer wn-cols-' . $feedCount;

    if (isset($_GET[WN_KEY_SCROLL]) && $_GET[WN_KEY_SCROLL] === 'sync') {
        //  Default is scroll-free
        $wrapperClass .= ' wn-scroll-sync';
    }

    if (isset($_GET[WN_KEY_IMAGES])) {
        //  Default is images-small
        switch ($_GET[WN_KEY_IMAGES]) {
            case 'large':
                $wrapperClass .= ' wn-images-large';
                break;
            case 'none':
                $wrapperClass .= ' wn-images-hide';
                break;
        }
    }

    if (isset($_GET[WN_KEY_DESCRIPTION])) {
        //  Default is description-none
        switch ($_GET[WN_KEY_DESCRIPTION]) {
            case 'full':
                $wrapperClass .= ' wn-description-full';
                break;
            case 'short':
                $wrapperClass .= ' wn-description-short';
                break;
        }
    }

    return $wrapperClass;
}

/**
 * Check a for a value in the $_GET array
 *
 * @param string $key
 * @param string $value
 * @param boolean $asDefault
 * @return boolean
 */
function checkGet($key, $value, $asDefault = false)
{
    if ($asDefault) {
        return !isset($_GET[$key]) || $_GET[$key] === $value;
    }

    return isset($_GET[$key]) && $_GET[$key] === $value;
}
