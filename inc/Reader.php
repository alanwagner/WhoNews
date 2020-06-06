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
     * @param string $title
     * @param int $limit
     * @return array
     */
    public function getChannelData($url, $title=null, $limit=null) {
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

            $data = $this->parseChannelContent($content, $limit);
            if (!empty($title)) {
                $data[WN_DATA_FEED_TITLE] = $title;
            }
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
     * @return array
     */
    protected function parseChannelContent($content, $limit=null)
    {
        $xml=simplexml_load_string($content);
        $channel = $xml->channel;

        $data[WN_DATA_FEED_TITLE] = $channel->title;
        $data[WN_DATA_FEED_ITEMS] = [];

        foreach($channel->item as $item) {

            $itemData = [];

            $guid = $item->guid;
            if (!is_string($guid)) {
                $guid = $guid->__toString();
            }
            $itemData[WN_DATA_ITEM_GUID] = strip_tags($guid);

            $title = $item->title;
            if (!is_string($title)) {
                $title = $title->__toString();
            }
            $itemData[WN_DATA_ITEM_TITLE] = strip_tags($title);

            if (empty($itemData[WN_DATA_ITEM_GUID]) || empty($itemData[WN_DATA_ITEM_TITLE])) {
                continue;
            }

            $description = $item->description;
            if (!is_string($description)) {
                $description = $description->__toString();
            }
            if (strstr($description, '<')) {
                //  Clean tags and embedded links
                $description = strip_tags(substr($description, 0, strpos($description, '<', 13)));
            }
            //  Eliminate title if also is beginning of description. May result in empty description.
            if (!empty($description) && strpos($description, $title) === 0) {
                $description = str_replace($title, '', $description);
            }
            $itemData[WN_DATA_ITEM_DESCRIPTION] = $description;

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

                //print_r($media->group->content->attributes());

            } else if (isset($media->content)) {


                //  NYT
                $attrs = $media->content->attributes();
                $itemData[WN_DATA_ITEM_IMAGE_URL] = strval($attrs['url']);

                //print_r($media->content->attributes());
            }

            if (isset($media->thumbnail)) {

                //  BuzzFeed has only thumbnail
                $attrs = $media->thumbnail->attributes();
                $itemData[WN_DATA_ITEM_THUMB_URL] = strval($attrs['url']);
            }


            $data[WN_DATA_FEED_ITEMS][] = $itemData;

            if (!empty($limit) && count($data[WN_DATA_FEED_ITEMS]) >= $limit) {
                break;
            }
        }

        return $data;
    }
}