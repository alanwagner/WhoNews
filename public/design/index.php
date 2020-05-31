<?php

function getChannelData($url) {
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

        $xml=simplexml_load_string($content);
        $channel = $xml->channel;

        $data['title'] = $channel->title;
        $data['items'] = [];

        foreach($channel->item as $item) {

            $itemData = [];

            $guid = $item->guid;
            if (!is_string($guid)) {
                $guid = $guid->__toString();
            }
            $itemData['guid'] = strip_tags($guid);

            $title = $item->title;
            if (!is_string($title)) {
                $title = $title->__toString();
            }
            $itemData['title'] = strip_tags($title);

            $description = $item->description;
            if (!is_string($description)) {
                $description = $description->__toString();

                // BuzzFeed puts image in description
                if (preg_match("#<img[^>]* src=.([^ >]+)[ >]#i", $description, $matches)) {
                    //  matches will contain trailing "'
                    // $itemData['image'] = substr($matches[1], 0, -1);
                }
            }
            if (strstr($description, '<')) {
                $description = strip_tags(substr($description, 0, strpos($description, '<', 10)));
            }
            $itemData['description'] = $description;

            $pubTime = strtotime($item->pubDate);
            if ($pubTime > 1000000000) {
                $itemData['pubDate'] = sprintf('%s GMT', gmdate('Y.m.d H:i', $pubTime));
            }


            $media = $item->children('http://search.yahoo.com/mrss/');

             if (isset($media->group->content)) {

                //  Fox
                $attrs = $media->group->content->attributes();
                $itemData['imgUrl'] = strval($attrs['url']);

                //print_r($media->group->content->attributes());

            } else if (isset($media->content)) {


                //  NYT
                $attrs = $media->content->attributes();
                $itemData['imgUrl'] = strval($attrs['url']);

                //print_r($media->content->attributes());
            }

            if (isset($media->thumbnail)) {

                //  BuzzFeed only has thumbnail
                $attrs = $media->thumbnail->attributes();
                $itemData['thumbUrl'] = strval($attrs['url']);
            }


            $data['items'][] = $itemData;

            if (count($data['items']) > 1) {
                break;
            }
        }
    }

    return $data;
}

$feedData = [];
foreach ($_GET['feed'] as $url) {
    $feedData[] = getChannelData($url);
}
if (empty($feedData)) {
    $feedData[] = getChannelData('https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml');
}


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>WhoNews : MVP</title>

<link href="../css/bootstrap.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-theme.css" media="screen" rel="stylesheet" type="text/css" />
<link href="whonews-design.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="tablet-outer wn-cols-<?php echo count($feedData) ?>">

    <div class="wn-top-header">
<!--
        <div class="wn-header-burger">&#9776;</div>
        <div class="wn-header-beta">Beta</div>
-->
        <div class="wn-header-title">WhoNews</div>
    </div>

    <div class="wn-category-header">
        Pop Your Info Bubble!
    </div>

    <div class="wn-tabs clearfix">
        <?php
        foreach($feedData as $idx => $feed):
            $tabClass = 'wn-tab ' . ($idx === count($feedData) - 1 ? 'wn-tab-left' : '');
        ?>
            <div class="<?php echo $tabClass ?>">
                <?php echo $feed['title'] ?>
            </div>
        <?php
        endforeach;
        ?>
    </div>




  <div class="tablet-inner">


    <?php
    foreach($feedData as $idx => $feed):
        $wrapperClass = 'wn-links-wrapper ' . ($idx === count($feedData) - 1 ? 'wn-links-left' : '');
    ?>
        <div class="<?php echo $wrapperClass ?>">
        <?php
        foreach($feed['items'] as $item):
            $imgUrl = null;
            if (!empty($item['imgUrl'])) {
            	$imgUrl = $item['imgUrl'];
            } else if (!empty($item['thumbUrl'])) {
                $imgUrl = $item['thumbUrl'];
            }
        ?>
            <a href="<?php echo $item['guid'] ?>" class="">
                <?php
                if (!empty($imgUrl)):
                ?>
                <span class="wn-link-image">
                    <img src="<?php echo $imgUrl ?>" />
                </span>
                <?php
                endif;
                ?>
                <span class="wn-link-text">
    	            <span class="wn-link-title"><?php echo $item['title'] ?></span>
    	            <span class="wn-link-date"><?php echo $item['pubDate'] ?></span>
    	            <span class="wn-link-description"><?php echo $item['description'] ?></span>
                </span>
            </a>

        <?php
        endforeach;
        ?>
        </div><!-- .wn-links-wrapper -->

    <?php
    endforeach;
    ?>

    <div class="clearfix"></div>

  </div><!-- .tablet-inner -->
</div><!-- .tablet-outer -->

</body>
</html>