<?php
/**
 * WhoNews : Template.php
 *
 * Methods and processing used in the front template
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

/**
 * Template class
 *
 * Encapsulates template variables and methods
 *
 * @author Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 *
 */
class Template
{
    /**  @var array  */
    public $query = [];

    /**  @var array  */
    public $queryFeeds = [];

    /**  @var array  */
    public $feedData = [];

    /**
     * Get page title, based on feedData and existence of query string
     *
     * @return string
     */
    public function getPageTitle()
    {
        $pageTitle = 'WhoNews Beta | ';

        if (isset($this->query[WN_KEY_FEED])) {
            foreach ($this->feedData as $idx => $feed) {
                $pageTitle .= $feed[WN_DATA_FEED_LABEL];
                if ($idx !== count($this->feedData) - 1) {
                    $pageTitle .= ' | ';
                }
            }
        } else {
            $pageTitle .= 'Pop Your Info Bubble';
        }

        return $pageTitle;
    }

    /**
     * Get class for main wrapper element
     *
     * @return string
     */
    public function getWrapperClass()
    {
        $wrapperClass = 'wn-tablet-outer wn-cols-' . count($this->feedData);

        if (isset($this->query[WN_KEY_SCROLL]) && $this->query[WN_KEY_SCROLL] === 'free') {
            //  Default is scroll-sync
            $wrapperClass .= ' wn-scroll-free';
        }

        if (isset($this->query[WN_KEY_IMAGES])) {
            //  Default is images-small
            switch ($this->query[WN_KEY_IMAGES]) {
                case 'large':
                    $wrapperClass .= ' wn-images-large';
                    break;
                case 'none':
                    $wrapperClass .= ' wn-images-hide';
                    break;
            }
        }

        if (isset($this->query[WN_KEY_DESCRIPTION])) {
            //  Default is description-none
            switch ($this->query[WN_KEY_DESCRIPTION]) {
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
     * Check a for a value in the query array
     *
     * @param string $key
     * @param string $value
     * @param boolean $asDefault
     * @return boolean
     */
    public function checkQuery($key, $value, $asDefault = false)
    {
        if ($asDefault) {
            return !isset($this->query[$key]) || $this->query[$key] === $value;
        }

        return isset($this->query[$key]) && $this->query[$key] === $value;
    }

    /**
     * Format description
     *
     * @param $descr
     * @return string
     */
    public function formatDescription($descr)
    {
        if (strstr($this->getWrapperClass(), ' wn-description-short') && strlen($descr) > 120) {
            $descr = substr($descr, 0, strrpos(substr($descr, 0, 120), ' ')) . '...';
        }

        return $descr;
    }

    /**
     * Get target for links, based on key in query
     *
     * @return string
     */
    public function getTarget()
    {
        $target = '';
        if (!isset($this->query[WN_KEY_TARGET]) || $this->query[WN_KEY_TARGET] === 'new') {
            $target = ' target="_blank"';
        }

        return $target;
    }

    /**
     * Should we display the inputs for a custom feed element?
     *
     * @param int $idx
     * @return boolean
     */
    public function displayCustom($idx)
    {
        $feedList = include(__DIR__ . '/feedlist.php');

        //  If feed is filled in, then it's custom only if it's not on the list

        if (isset($this->queryFeeds[$idx])) {

            return !isset($feedList[$this->queryFeeds[$idx]]);
        }

        //  No feed. Show custom if :
        //    is last element and
        //    previous element is also empty and
        //    there's not already a custom feed in the list

        if ($idx + 1 === WN_MAX_FEEDS && count($this->queryFeeds) < WN_MAX_FEEDS - 1) {
            for ($i = 0; $i < count($this->queryFeeds); $i++) {
                if (!isset($feedList[$this->queryFeeds[$i]])) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }
}
