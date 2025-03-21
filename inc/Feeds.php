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
        'npr-news',
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


        'bbc' => [
            'title'     => 'BBC',
            'label'     => 'BBC',
            'img'       => 'bbc_banner.png',
            'feeds' => [
	            'top' => [
                    'name' => 'Top Stories',
                    'url'  => 'https://feeds.bbci.co.uk/news/rss.xml',
                ],
                'us' => [
                    'name' => 'U.S. &amp; Canada',
                    'url' => 'https://feeds.bbci.co.uk/news/world/us_and_canada/rss.xml',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'https://feeds.bbci.co.uk/news/world/rss.xml',
                ],
                'business' => [
                    'name' => 'Business',
                    'url'  => 'https://feeds.bbci.co.uk/news/business/rss.xml',
                ],
            ],
        ],

        //  Feed has no image data
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


        //  Feeds have no image data
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
                    'url'  => 'https://moxie.foxnews.com/google-publisher/latest.xml',
                ],
                'national' => [
                    'name' => 'U.S.',
                    'url'  => 'https://moxie.foxnews.com/google-publisher/us.xml',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'https://moxie.foxnews.com/google-publisher/world.xml',
                ],
                'opinion' => [
                    'name' => 'Opinion',
                    'url'  => 'https://moxie.foxnews.com/google-publisher/opinion.xml',
                ],
                'politics' => [
                    'name' => 'Politics',
                    'url'  => 'https://moxie.foxnews.com/google-publisher/politics.xml',
                ],
            ],
        ],


        //  Feeds have no image data
        'huffpost' => [
            'title'     => 'Huff Post',
            'label'     => 'Huffington Post',
            'img'       => 'huffpost-logo-1.png',
            'feeds' => [
                'us' => [
                    'name' => 'U.S.',
                    'url'  => 'https://chaski.huffpost.com/us/auto/vertical/us-news',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'https://chaski.huffpost.com/us/auto/vertical/world-news',
                ],
                'politics' => [
                    'name' => 'U.S. Politics',
                    'url'  => 'https://chaski.huffpost.com/us/auto/vertical/politics',
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

        //  Some feeds have no image data
        'politico' => [
            'title'     => 'Politico',
            'label'     => 'Politico',
            'img'       => 'POLITICO_Logo.jpg',
            'feeds' => [
                'politics' => [
                    'name' => 'Politics News',
                    'url'  => 'https://rss.politico.com/politics-news.xml',
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


        'theguardian' => [
            'title'     => 'The Guardian',
            'label'     => 'The Guardian',
            'img'       => 'guardian-logo-rss.png',
            'feeds' => [
                'international' => [
                    'name' => 'International Edition',
                    'url'  => 'https://www.theguardian.com/international/rss',
                ],
                'world' => [
                    'name' => 'World News',
                    'url'  => 'https://www.theguardian.com/world/rss',
                ],
                'usedition' => [
                    'name' => 'U.S. Edition',
                    'url'  => 'https://www.theguardian.com/us/rss',
                ],
                'usnews' => [
                    'name' => 'U.S. News',
                    'url'  => 'https://www.theguardian.com/us-news/rss',
                ],
                'opinion' => [
                    'name' => 'U.S. Opinion',
                    'url'  => 'https://www.theguardian.com/us/commentisfree/rss',
                ],
                'business' => [
                    'name' => 'U.S. Business',
                    'url'  => 'https://www.theguardian.com/us/business/rss',
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

        //  Feeds have no image data
        'wapo' => [
            'title'     => 'WaPo',
            'label'     => 'Washington Post',
            'img'       => 'wapo.png',
            'feeds' => [
                'national' => [
                    'name' => 'U.S.',
                    'url'  => 'https://feeds.washingtonpost.com/rss/national',
                ],
                'world' => [
                    'name' => 'World',
                    'url'  => 'https://feeds.washingtonpost.com/rss/world',
                ],
                'opinion' => [
                    'name' => 'Opinions',
                    'url'  => 'https://feeds.washingtonpost.com/rss/opinions',
                ],
                'politics' => [
                    'name' => 'Politics',
                    'url'  => 'https://feeds.washingtonpost.com/rss/politics',
                ],
                'business' => [
                    'name' => 'Business',
                    'url'  => 'https://feeds.washingtonpost.com/rss/business',
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
