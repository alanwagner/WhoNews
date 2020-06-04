<?php
/**
 * WhoNews : url.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

/**  Redirect to shortest possible (canonical) URL, from Settings form submission  **/

$q = $_REQUEST;

//  Unset default values

$defaults = [
    WN_KEY_SCROLL => WN_DEFAULT_SCROLL,
    WN_KEY_IMAGES => WN_DEFAULT_IMAGES,
    WN_KEY_DESCRIPTION => WN_DEFAULT_DESCRIPTION,
    WN_KEY_TARGET => WN_DEFAULT_TARGET,
];

foreach ($defaults as $key => $val) {
    if (isset($q[$key]) && $q[$key] === $val) {
        unset($q[$key]);
    }
}


//  Optimize 'feed' array, copying in 'custom' elements

if (isset($q[WN_KEY_FEED])) {
    if (isset($q[WN_KEY_CUSTOM])) {

        //  Check custom values, copying them into 'feed' array and eliminating invalid elements

        $feed = $q[WN_KEY_FEED];

        foreach ($q[WN_KEY_FEED] as $key => $val) {

            if ($val === 'custom') {

                if (!empty($q[WN_KEY_CUSTOM][$key])) {
                    $feed[$key] = $q[WN_KEY_CUSTOM][$key];
                } else {
                    //  Invalid 'custom' value for feed
                    //  array_filter below will eliminate it
                    $feed[$key] = null;
                }
            }
        }

        $q[WN_KEY_FEED] = $feed;
    }
    unset($q[WN_KEY_CUSTOM]);

    //  Optimize keys of feed
    $q[WN_KEY_FEED] = array_merge(array_filter($q[WN_KEY_FEED]));

    if (empty($q[WN_KEY_FEED])) {
        unset($q[WN_KEY_FEED]);
    }
}


//  Redirect to canonical URL if any changes made to query

$query = http_build_query($q);
if ($query !== http_build_query($_REQUEST)) {

    header("Location:?" .$query);
    exit;
}

