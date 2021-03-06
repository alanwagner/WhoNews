<?php
/**
 * WhoNews : Feeds.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

/**
 * Feeds class
 *
 * List of feeds and methods for info about the list
 *
 * @author Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 *
 */
class Feeds
{
    /** @var string **/
    const SOURCE_TITLE = 'title';

    /** @var string **/
    const SOURCE_LABEL = 'label';

    /** @var string **/
    const SOURCE_IMAGE = 'img';

    /** @var string **/
    const FEED_URL = 'url';

    /** @var string **/
    const FEED_NAME = 'name';

    /**
     * The default feeds to display, if none are indicated in the url
     *
     * @var array
     */
    public static $defaultFeeds = [
        'huffpost-breaking',
        'nyt-homepage',
        'foxnews-latest',
    ];

    /**
     * The full list of feeds and their individual data
     *
     * @var array
     */
    public static $list = [


        //  Feed has no image data
        'aljazeera' => [
            'title'     => 'Al Jazeera',
            'label'     => 'Al Jazeera',
            'img'       => 'aljazeera_banner_large.jpg',
            'feeds'     => [
                'english' => [
                    'name' => 'English',
                    'url'  => 'https://www.aljazeera.com/xml/rss/all.xml',
                ],
            ],
        ],


        //  Feeds have no image data
        'bbc' => [
            'title'     => 'BBC',
            'label'     => 'BBC',
            'img'       => 'bbc_banner.png',
            'feeds' => [
	            'top' => [
                    'name' => 'Top Stories',
                    'url'  => 'http://feeds.bbci.co.uk/news/rss.xml',
                ],
                'us' => [
                    'name' => 'U.S. &amp; Canada',
                    'url' => 'http://feeds.bbci.co.uk/news/world/us_and_canada/rss.xml',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'http://feeds.bbci.co.uk/news/world/rss.xml',
                ],
                'business' => [
                    'name' => 'Business',
                    'url'  => 'http://feeds.bbci.co.uk/news/business/rss.xml',
                ],
            ],
        ],


        'breitbart' => [
            'title'     => 'Breitbart',
            'label'     => 'Breitbart',
            'img'       => 'bb_horizontal-p-800.png',
            'feeds' => [
                'news' => [
                    'name' => 'News',
                    'url'  => 'http://feeds.feedburner.com/breitbart',
                ],
            ],
        ],


        'buzzfeed' => [
            'title'     => 'BuzzFeed',
            'label'     => 'BuzzFeed',
            'img'       => 'buzzfeed_logo-news.png',
            'feeds' => [
                'news' => [
                    'name' => 'News',
                    'url'  => 'https://www.buzzfeednews.com/news.xml',
                    'img'  => 'bfn-logo-small.png',
                ],
                'latest' => [
                    'name' => 'Latest',
                    'url'  => 'https://www.buzzfeed.com/index.xml',
                    'img'  => 'buzzfeed_logo.png',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'https://www.buzzfeed.com/world.xml',
                ],
                'reader' => [
                    'name' => 'Reader',
                    'url'  => 'https://www.buzzfeed.com/reader.xml',
                ],
                'business' => [
                    'name' => 'Business',
                    'url'  => 'https://www.buzzfeed.com/business.xml',
                ],
            ],
        ],


        'cnn' => [
            'title'     => 'CNN',
            'label'     => 'CNN',
            'img'       => 'cnn-com-logo.png',
            'feeds' => [
                'top' => [
                    'name' => 'Top Stories',
                    'url'  => 'http://rss.cnn.com/rss/edition.rss',
                ],
                'latest' => [
                    'name' => 'Most Recent',
                    'url'  => 'http://rss.cnn.com/rss/cnn_latest.rss',
                ],
                'us' => [
                    'name' => 'U.S.',
                    'url'  => 'http://rss.cnn.com/rss/cnn_us.rss',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'http://rss.cnn.com/rss/cnn_world.rss',
                ],
                'money' => [
                    'name' => 'Money',
                    'url'  => 'http://rss.cnn.com/rss/money_news_international.rss',
                ],
            ],
        ],


        'csm' => [
            'title'     => 'CS Monitor',
            'label'     => 'Christian Science Monitor',
            'img'       => 'csm-masthead_232x60.png',
            'feeds' => [
                'all' => [
                    'name' => 'All Stories',
                    'url'  => 'https://rss.csmonitor.com/feeds/all',
                ],
                'usa' => [
                    'name' => 'U.S.',
                    'url'  => 'https://rss.csmonitor.com/feeds/usa',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'https://rss.csmonitor.com/feeds/world',
                ],
                'commentary' => [
                    'name' => 'Commentary',
                    'url'  => 'https://rss.csmonitor.com/feeds/commentary',
                ],
                'politics' => [
                    'name' => 'Politics',
                    'url'  => 'https://rss.csmonitor.com/feeds/politics',
                ],
                'business' => [
                    'name' => 'Business',
                    'url'  => 'https://rss.csmonitor.com/feeds/wam',
                ],
            ],
        ],

        //  Feed has no image data, and description is all extraneous html
        'drudgereport' => [
            'title'     => 'Drudge Report',
            'label'     => 'Drudge Report',
            'img'       => 'drudge_logo9.gif',
            'feeds' => [
                'news' => [
                    'name' => 'News',
                    'url'  => 'https://feeds.feedburner.com/DrudgeReportFeed',
                ],
            ],
        ],


        'foxnews' => [
            'title'     => 'FOX News',
            'label'     => 'FOX News',
            'img'       => 'fox-news-logo-banner.png',
            'feeds' => [
                'latest' => [
                    'name' => 'Latest',
                    'url'  => 'http://feeds.foxnews.com/foxnews/latest',
                ],
                'national' => [
                    'name' => 'U.S.',
                    'url'  => 'http://feeds.foxnews.com/foxnews/national',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'http://feeds.foxnews.com/foxnews/world',
                ],
                'opinion' => [
                    'name' => 'Opinion',
                    'url'  => 'http://feeds.foxnews.com/foxnews/opinion',
                ],
                'politics' => [
                    'name' => 'Politics',
                    'url'  => 'http://feeds.foxnews.com/foxnews/politics',
                ],
            ],
        ],


        'huffpost' => [
            'title'     => 'Huff Post',
            'label'     => 'Huffington Post',
            'img'       => 'huffpost-logo-1.png',
            'feeds' => [
                'breaking' => [
                    'name' => 'Breaking',
                    'url'  => 'https://www.huffpost.com/section/front-page/feed',
                ],
                'us' => [
                    'name' => 'U.S.',
                    'url'  => 'https://www.huffpost.com/section/us-news/feed',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'https://www.huffpost.com/section/world-news/feed',
                ],
                'politics' => [
                    'name' => 'U.S. Politics',
                    'url'  => 'https://www.huffpost.com/section/politics/feed',
                ],
                'business' => [
                    'name' => 'Business',
                    'url'  => 'https://www.huffpost.com/section/business/feed',
                ],
            ],
        ],


        'nyt' => [
            'title' => 'NYT',
            'label'     => 'New York Times',
            'img'       => 'nyt.jpg',
            'feeds' => [
                'homepage' => [
                    'name' => 'Home Page',
                    'url'  => 'https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml',
                ],
                'popular' => [
                    'name' => 'Most Popular',
                    'url'  => 'https://rss.nytimes.com/services/xml/rss/nyt/MostEmailed.xml',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'https://rss.nytimes.com/services/xml/rss/nyt/World.xml',
                ],
                'opinion' => [
                    'name' => 'Opinion',
                    'url'  => 'https://rss.nytimes.com/services/xml/rss/nyt/Opinion.xml',
                ],
                'politics' => [
                    'name' => 'U.S. Politics',
                    'url'  => 'https://rss.nytimes.com/services/xml/rss/nyt/Politics.xml',
                ],
                'business' => [
                    'name' => 'Business',
                    'url'  => 'https://rss.nytimes.com/services/xml/rss/nyt/Business.xml',
                ],
            ],
        ],


        'npr' => [
            'title'     => 'NPR',
            'label'     => 'NPR',
            'img'       => 'npr_generic_image_300.jpg',
            'feeds' => [
                'news' => [
                    'name' => 'News',
                    'url'  => 'https://feeds.npr.org/1001/rss.xml',
                ],
            ],
        ],


        'politico' => [
            'title'     => 'Politico',
            'label'     => 'Politico',
            'img'       => 'POLITICO_Logo.jpg',
            'feeds' => [
                'politics' => [
                    'name' => 'Politics News',
                    'url'  => 'https://rss.politico.com/politics.xml',
                ],
                'economy' => [
                    'name' => 'Economy',
                    'url'  => 'https://rss.politico.com/economy.xml',
                ],
                //  These Politico feeds have no image data:
                'playbook' => [
                    'name' => 'Playbook',
                    'url'  => 'https://rss.politico.com/playbook.xml',
                ],
                'money' => [
                    'name' => 'Morning Money',
                    'url'  => 'https://rss.politico.com/morningmoney.xml',
                ],
                'trade' => [
                    'name' => 'Morning Trade',
                    'url'  => 'https://rss.politico.com/morningtrade.xml',
                ],
            ],
        ],


        'usatoday' => [
            'title'     => 'USA Today',
            'label'     => 'USA Today',
            'img'       => 'usa-today.png',
            'feeds' => [
                'top' => [
                    'name' => 'Top Stories',
                    'url'  => 'http://rssfeeds.usatoday.com/usatoday-NewsTopStories',
                ],
                'us' => [
                    'name' => 'U.S.',
                    'url'  => 'http://rssfeeds.usatoday.com/UsatodaycomNation-TopStories',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'http://rssfeeds.usatoday.com/UsatodaycomWorld-TopStories',
                ],
                'politics' => [
                    'name' => 'Politics',
                    'url'  => 'http://rssfeeds.usatoday.com/UsatodaycomWashington-TopStories',
                ],
                'money' => [
                    'name' => 'Money',
                    'url'  => 'http://rssfeeds.usatoday.com/UsatodaycomMoney-TopStories',
                ],
            ],
        ],


        //  Only last feed has image data
        'wsj' => [
            'title'     => 'WSJ',
            'label'     => 'Wall Street Journal',
            'img'       => 'wsj_header2-870x276.jpg',
            'feeds' => [
                'world' => [
                    'name' => 'World',
                    'url'  => 'https://feeds.a.dj.com/rss/RSSWorldNews.xml',
                ],
                'opinion' => [
                    'name' => 'Opinion',
                    'url'  => 'https://feeds.a.dj.com/rss/RSSOpinion.xml',
                ],
                'business' => [
                    'name' => 'U.S. Business',
                    'url'  => 'https://feeds.a.dj.com/rss/WSJcomUSBusiness.xml',
                ],
            ],
        ],


        'wapo' => [
            'title'     => 'WaPo',
            'label'     => 'Washington Post',
            'img'       => 'wapo.png',
            'feeds' => [
                'national' => [
                    'name' => 'U.S.',
                    'url'  => 'http://feeds.washingtonpost.com/rss/national',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'http://feeds.washingtonpost.com/rss/world',
                ],
                'opinion' => [
                    'name' => 'Opinions',
                    'url'  => 'http://feeds.washingtonpost.com/rss/opinions',
                ],
                'politics' => [
                    'name' => 'Politics',
                    'url'  => 'http://feeds.washingtonpost.com/rss/politics',
                ],
                'business' => [
                    'name' => 'Business',
                    'url'  => 'http://feeds.washingtonpost.com/rss/business',
                ],
            ],
        ],
    ];

    /**
     * Is this feed an unconfigured custom feed, based on its key / url?
     *
     * @param string $key
     * @return boolean
     */
    public static function isCustomFeed($key)
    {
        if (
            preg_match('/^([a-z]+)-([a-z]+)$/', $key, $matches) === 1
            && isset(self::$list[$matches[1]]['feeds'][$matches[2]])
        ) {
        	return false;
        }

        return true;
    }

    /**
     * Get config for a given feed
     * Returns null if feed not found
     *
     * @param string $key
     * @return array|null
     */
    public static function getFeedConfig($key)
    {
        $config = null;

        if (
            preg_match('/^([a-z]+)-([a-z]+)$/', $key, $matches) === 1
            && isset(self::$list[$matches[1]]['feeds'][$matches[2]])
        ) {
            $source = self::$list[$matches[1]];
            $feed = $source['feeds'][$matches[2]];
            $config = [
                'title' => $source['title'],
                'label' => $source['label'],
                'img'   => $source['img'],
                'name'  => $feed['name'],
                'url'   => $feed['url'],
            ];
            if (isset($feed['img'])) {
                $config['img'] = $feed['img'];
            }
        }

        return $config;
    }

    /**
     * Get config data for all feeds
     *
     * @return array
     */
    public static function getAllFeeds()
    {
        $feeds = [];
        foreach (self::$list as $source => $conf) {
            foreach ($conf['feeds'] as $feed => $data) {
                $key = $source . '-' . $feed;
                $feeds[$key] = self::getFeedConfig($key);
            }
        }

        return $feeds;
    }

    /**
     * Get the number of feeds
     *
     * @return int
     */
    public static function countFeeds()
    {
        return substr_count(json_encode(self::$list), '"url":');
    }

    /**
     * Get the number of distinct sources
     *
     * @return int
     */
    public static function countSources()
    {
        return count(self::$list);
    }
}
