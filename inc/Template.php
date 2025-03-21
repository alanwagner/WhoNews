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
                $pageTitle .= self::formatFeedLabel($feed);
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
        $wrapperClass = 'wn-cols-' . count($this->queryFeeds);

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
                //  This case is technically superfluous, since the image tags themselves won't be created
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
     * @param string $descr
     * @param bool $escape
     * @return string
     */
    public function formatDescription($descr, $escape = false)
    {
        if (isset($this->query[WN_KEY_DESCRIPTION]) &&
                $this->query[WN_KEY_DESCRIPTION] === 'short' &&
                strlen($descr) > 120) {
            $descr = substr($descr, 0, strrpos(substr($descr, 0, 120), ' ')) . '...';
        }
        $descr = str_replace(chr(194) . chr(160), ' ', $descr);

        if ($escape === true) {
            $descr = self::escape($descr);
        }

        return $descr;
    }

    /**
     * Get target for links, based on key in query
     *
     * @param bool $escape
     * @return string
     */
    public function getTarget($escape = false)
    {
        $target = '';
        if (!isset($this->query[WN_KEY_TARGET]) || $this->query[WN_KEY_TARGET] === 'new') {
            $target = ' target="_blank"';
        }

        if ($escape === true) {
            $target = self::escape($target);
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
        $feedList = Feeds::$list;

        //  If feed is filled in, then it's custom only if it's not on the list

        if (isset($this->queryFeeds[$idx])) {

            return Feeds::isCustomFeed($this->queryFeeds[$idx]);
        }

        //  No feed. Show custom if :
        //    is last element and previous element is also empty
        //    and there's not already a custom feed in the list

        if ($idx + 1 === WN_MAX_FEEDS && count($this->queryFeeds) < WN_MAX_FEEDS - 1) {

            for ($i = 0; $i < count($this->queryFeeds); $i++) {
                if (Feeds::isCustomFeed($this->queryFeeds[$i])) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Get column classes for css (wn-col-left, wn-col-right)
     *
     * @param int $idx
     * @return string
     */
    public function getColumnClass($idx)
    {
        $colClass = '';
        if ($idx === 0) {
            $colClass .= ' wn-col-left';
        }
        if ($idx === count($this->queryFeeds) -1) {
            $colClass .= ' wn-col-right';
        }

        return $colClass;
    }

    /**
     * Get url for item's image, if it has one
     * Returns false if item does not have an image
     * Returns null if user settings have images-none
     *
     * @param array $item
     * @return string|false|null
     */
    public function getItemImageUrl($item)
    {
        if ($this->displayImages() === false) {
            return null;
        }

        $imgUrl = false;
        if (!empty($item[WN_DATA_ITEM_IMAGE_URL])) {
            $imgUrl = $item[WN_DATA_ITEM_IMAGE_URL];
        } else if (!empty($item[WN_DATA_ITEM_THUMB_URL])) {
            $imgUrl = $item[WN_DATA_ITEM_THUMB_URL];
        }

        return $imgUrl;
    }

    /**
     * Should we display images, based on the user's settings?
     *
     * @return boolean
     */
    public function displayImages()
    {
        //  Default is images-small
        if (isset($this->query[WN_KEY_IMAGES]) && $this->query[WN_KEY_IMAGES] === 'none') {

            return false;
        }

        return true;
    }

    /**
     * Get html-safe filter string
     *
     * @return string
     */
    public function getFilterString()
    {
        $filter = '';
        if (isset($this->query[WN_KEY_FILTER])) {
            $filter = htmlspecialchars(trim($this->query[WN_KEY_FILTER]), ENT_QUOTES);
        }

        return $filter;
    }

    /**
     * Get formatted feed label from feed data
     *
     * @param array $feed
     * @param bool $escape
     * @return string
     */
    public static function formatFeedLabel($feed, $escape = false)
    {
        $label = $feed[Feeds::SOURCE_LABEL];
        if ($feed[Feeds::FEED_NAME] !== null) {
            $label .= ' : ' . $feed[Feeds::FEED_NAME];
        }

        if ($escape === true) {
            $label = self::escape($label);
        }

        return $label;
    }

    /**
     * Get formatted feed title from feed data
     *
     * @param array $feed
     * @param bool $escape
     * @return string
     */
    public static function formatFeedTitle($feed, $escape = false)
    {
        $label = $feed[Feeds::SOURCE_TITLE];
        if ($feed[Feeds::FEED_NAME] !== null) {
            $label .= ' &nbsp;⟩&nbsp; ' . $feed[Feeds::FEED_NAME];
        }

        if ($escape === true) {
            $label = self::escape($label);
        }

        return $label;
    }

    /**
     * Escape double quotes for JSON output
     *
     * @param string $data
     * @return string
     */
    public static function escape($data)
    {
        return str_replace('"', '\"', $data);
    }
}
