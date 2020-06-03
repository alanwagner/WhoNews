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

if (isset($q['scroll']) && $q['scroll'] === 'free') {
    unset($q['scroll']);
}

if (isset($q['images']) && $q['images'] === 'show') {
    unset($q['images']);
}

if (isset($q['description']) && $q['description'] === 'none') {
    unset($q['description']);
}


//  Optimize 'feed' array, copying in 'custom' elements

if (isset($q['feed'])) {
    if (isset($q['custom'])) {

        //  Check custom values, copying them into 'feed' array and eliminating invalid elements
        $feed = $q['feed'];
        foreach ($q['feed'] as $key => $val) {
            if ($val === 'custom') {
                if (empty($q['custom'][$key])) {
                    //  Invalid 'custom' value for feed
                    //  array_filter below will eliminate it
                    $feed[$key] = null;

                } else {
                    $feed[$key] = $q['custom'][$key];
                }
            }
        }

        $q['feed'] = $feed;
    }
    unset($q['custom']);

    //  Optimize keys of feed
    $q['feed'] = array_merge(array_filter($q['feed']));

    if (empty($q['feed'])) {
        unset($q['feed']);
    }
}


//  Redirect to canonical URL if any changes made to query

$query = http_build_query($q);
if ($query !== http_build_query($_REQUEST)) {

    header("Location:?" .$query);
    exit;
}

