<?php
/**
 * WhoNews : url.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

/**  Redirect to shortest possible (canonical) URL  **/

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


//  Unset empty / invalid 'feed' and 'custom' elements

if (!isset($q['feed'])) {
    unset($q['custom']);
} else {
    if (isset($q['custom'])) {

        //  Check custom values, building a fresh list and eliminating invalid 'feed' custom elements
        $custom = [];
        $feed = $q['feed'];
        foreach ($q['feed'] as $key => $val) {
            if ($val === 'custom') {
                if (empty($q['custom'][$key])) {
                    //  Invalid 'custom' value for feed
                    //  array_filter below will eliminate it
                    $feed[$key] = null;

                } else {
                    $custom[] = $q['custom'][$key];
                }

            }
        }

        //  Now optimize keys of feed

        $q['feed'] = array_merge(array_filter($feed));

        //  Map custom values
        if (empty($custom)) {
            unset($q['custom']);
        } else {
            $q['custom'] = [];
            $idx = 0;
            foreach($q['feed'] as $key => $val) {
                if ($val === 'custom') {
                    $q['custom'][$key] = $custom[$idx];
                    $idx++;
                }
            }
        }
    } else {
        //  Just optimize keys of feed
        $q['feed'] = array_merge(array_filter($q['feed']));
    }

}


//  Redirect to canonical URL if any changes made to query

$query = http_build_query($q);
if ($query !== http_build_query($_REQUEST)) {

    header("Location:?" .$query);
    exit;
}

