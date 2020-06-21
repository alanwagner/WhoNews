<?php
/**
 * WhoNews : Controller.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

include_once(__DIR__ . '/Reader.php');

/**
 * Controller class
 *
 * Encapsulates front controller variables and methods
 *
 * @author Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */
class Controller
{
    /**
     * Generate to shortest possible (canonical) URL, from request query (raw Settings form submission)
     * Returns null if query is already optimal
     *
     * @param array $query
     * @return string|null
     */
    public function optimizeUrl($query)
    {
        $q = $query;

        //  Unset default values

        $defaults = [
            WN_KEY_FILTER      => WN_DEFAULT_FILTER,
            WN_KEY_SCROLL      => WN_DEFAULT_SCROLL,
            WN_KEY_IMAGES      => WN_DEFAULT_IMAGES,
            WN_KEY_DESCRIPTION => WN_DEFAULT_DESCRIPTION,
            WN_KEY_LIMIT       => WN_DEFAULT_LIMIT,
            WN_KEY_TARGET      => WN_DEFAULT_TARGET,
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


        //  Build query and check if any changes were made

        $url = http_build_query($q);

        if ($url === http_build_query($query)) {
            $url = null;
        }

        return $url;
    }

    /**
     * Get list of feeds from query, or return default list
     *
     * @param array $query
     * @return array
     */
    public function getQueryFeeds($query)
    {
        if (isset($query[WN_KEY_FEED])) {

            return $query[WN_KEY_FEED];
        }

        return Feeds::$defaultFeeds;
    }

    /**
     *
     * Get data from each listed feed, parsed as php array
     *
     * @param array $queryFeeds
     * @param int|null $limit
     * @param string|null $filter
     * @return array
     */
    public function getFeedData($queryFeeds, $limit = null, $filter = null)
    {
        $reader = new Reader();
        $feedData = [];

        foreach ($queryFeeds as $idx => $url) {

            $config = Feeds::getFeedConfig($url);
            if ($config !== null) {
                $feedUrl = $config[Feeds::FEED_URL];
            } else {
                $feedUrl = $url;
            }

            $data = $reader->getChannelData($feedUrl, $limit, $filter);

            $data[Feeds::FEED_URL] = $feedUrl;

            //  Override the feed's own settings, if not a custom feed
            if ($config !== null) {
                $data[Feeds::SOURCE_TITLE] = $config[Feeds::SOURCE_TITLE];
                $data[Feeds::SOURCE_LABEL] = $config[Feeds::SOURCE_LABEL];
                $data[Feeds::SOURCE_IMAGE] = $config[Feeds::SOURCE_IMAGE];
                $data[Feeds::FEED_NAME]    = $config[Feeds::FEED_NAME];
            } else {
                $data[Feeds::SOURCE_IMAGE] = null;
                $data[Feeds::FEED_NAME]    = null;
            }

            $feedData[] = $data;
        }

        return $feedData;
    }
}

