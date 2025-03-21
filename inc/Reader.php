<?php
/**
 * WhoNews : Reader.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

/**
 * Reader class
 *
 * Methods to read and parse XML feed data
 *
 * @author Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 *
 */
class Reader
{
    /**
     * Read XML feed data from url and return parsed as php array
     *
     * @param string $url
     * @param int $limit
     * @param string $filter
     * @return array
     */
    public function getChannelData($url, $limit = null, $filter = null) {
        $data = [];
        $content = null;

        $ch = curl_init();

        ob_start();

        // Configuration de l'URL et d'autres options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Récupération de l'URL
        curl_exec($ch);

        // Fermeture de la session cURL
        curl_close($ch);

        $content = ob_get_contents();
        ob_end_clean();

        if (is_string($content)) {

            $data = $this->parseChannelContent($content, $limit, $filter);
        }

        return $data;
    }

    /**
     * Parse a channel's XML string into a PHP array
     *
     * Return is of the form:
     *   title : string
     *   items : array (guid, title, description, pubDate[, imgUrl, thumbUrl])
     *
     * @param string $content
     * @param int $limit
     * @param string $filter
     * @return array
     */
    protected function parseChannelContent($content, $limit = null, $filter = null)
    {
        $xml=simplexml_load_string($content);
        $channel = $xml->channel;

        $data[Feeds::SOURCE_TITLE] = $channel->title;
        $data[Feeds::SOURCE_LABEL] = $channel->title;
        $data[WN_DATA_FEED_ITEMS] = [];

        $filterRegex = null;
        if ($filter !== null) {
            $filters = explode(' ', $filter);
            $filters = join('|', array_filter($filters));
            $filterRegex = sprintf('/%s/i', str_replace('/', '\/', $filters));
        }

        foreach($channel->item as $item) {

            $itemData = [];

            $link = $item->link;
            if (!is_string($link)) {
                $link = $link->__toString();
            }
            $itemData[WN_DATA_ITEM_LINK] = strip_tags($link);

            $guid = $item->guid;
            if (!is_string($guid)) {
                $guid = $guid->__toString();
            }
            $itemData[WN_DATA_ITEM_GUID] = strip_tags($guid);

            if (substr($guid, 0, 4) === 'http') {
                $itemData[WN_DATA_ITEM_HREF] = $guid;
            } else {
                $itemData[WN_DATA_ITEM_HREF] = $link;
            }

            $title = $item->title;
            if (!is_string($title)) {
                $title = $title->__toString();
            }
            $itemData[WN_DATA_ITEM_TITLE] = strip_tags($title);

            if (empty($itemData[WN_DATA_ITEM_HREF]) || empty($itemData[WN_DATA_ITEM_TITLE])) {
                continue;
            }

            $description = $item->description;
            if (!is_string($description)) {
                $description = $description->__toString();
            }
            if (strstr($description, '<')) {

                //  Clean tags and embedded html
               if (substr($description, 0, 4) === '<h1>') {
                   $description = substr($description, 4, strpos($description, '</h1>') - 4);
               } elseif (substr($description, 0, 3) === '<p>') {
                   $description = substr($description, 3, strpos($description, '</p>') - 3);
               } else {
                   $description = substr($description, 0, strpos($description, '<'));
               }
            }
            //  Eliminate title if also is beginning of description. May result in empty description.
            if (!empty($description) && strpos($description, $title) === 0) {
                $description = str_replace($title, '', $description);
            }
            $itemData[WN_DATA_ITEM_DESCRIPTION] = $description;


            if ($filterRegex !== null) {
                if (preg_match($filterRegex, $itemData[WN_DATA_ITEM_TITLE]) !== 1
                    && preg_match($filterRegex, $itemData[WN_DATA_ITEM_DESCRIPTION]) !== 1
                ) {
                    continue;
                }
            }


            $pubTime = strtotime($item->pubDate);
            if ($pubTime > 1000000000) {
                //  Format 2020.05.31 11:11 GMT
                $itemData[WN_DATA_ITEM_PUB_DATE] = sprintf('%s GMT', gmdate('Y.m.d H:i', $pubTime));
            }

            $media = $item->children('http://search.yahoo.com/mrss/');

            if (isset($media->group->content)) {

                //  Fox
                $attrs = $media->group->content->attributes();
                $itemData[WN_DATA_ITEM_IMAGE_URL] = strval($attrs['url']);

            } else if (isset($media->content)) {


                //  NYT
                $attrs = $media->content->attributes();
                $itemData[WN_DATA_ITEM_IMAGE_URL] = strval($attrs['url']);
            }

            if (isset($media->thumbnail)) {

                //  BuzzFeed has only thumbnail
                $attrs = $media->thumbnail->attributes();
                $itemData[WN_DATA_ITEM_THUMB_URL] = strval($attrs['url']);
            }

            if (
                empty($itemData[WN_DATA_ITEM_IMAGE_URL])
                && empty($itemData[WN_DATA_ITEM_THUMB_URL])
            ) {

                if (isset($item->enclosure)) {
                    //  HuffPost images
                    $attrs = $item->enclosure->attributes();
                    if (isset($attrs['url'])) {
                        $itemData[WN_DATA_ITEM_IMAGE_URL] = strval($attrs['url']);
                    }
                } else {
                    $content = $item->children('content', 'http://purl.org/rss/1.0/modules/content/');
                    if (!empty($content->encoded)) {
                        $htmlContent = $content->encoded->__toString();

                        if (preg_match("/^<img[^>]+src=['\"](http[^'\"]+)['\"]/", $htmlContent, $matches)) {
                        //  NPR images
                            $itemData[WN_DATA_ITEM_IMAGE_URL] = $matches[1];

                        } else if (preg_match("/<img[^>]+src=\"(https:\/\/www.gannett-cdn.com[^\"]+)\"/", $htmlContent, $matches)) {
                            //  USA Today images
                            $itemData[WN_DATA_ITEM_IMAGE_URL] = $matches[1];
                        }
                    }
                }
            }


            $data[WN_DATA_FEED_ITEMS][] = $itemData;

            if (!empty($limit) && count($data[WN_DATA_FEED_ITEMS]) >= $limit) {
                break;
            }
        }

        return $data;
    }
}
