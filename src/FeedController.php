<?php
/**
 * Podster Package Controller File.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
namespace Concrete\Package\Podster\Src;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Controller\Controller;
use Route;
use Concrete\Core\Http\Response;
use DOMDocument;
use DateTime;

/**
 * Package Controller Class.
 *
 * Run a podcast from concrete5.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
class FeedController extends Controller
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function registerRoute()
    {
        Route::register('/podcasts/{showID}', [$this, 'handle']);
    }

    public function handle($showID)
    {
        // Find the show, otherwise return a 404.
        if (!($show = $this->app->make('podster/repositories/shows')->find($showID))) {
            return new Response('Not found.', 404);
        }

        // Generate the feed.
        $feedXml = $this->generateFeed($show);

        // Log the download.
        if (!isset($_GET['notracky'])) {
            $this->app->make('podster/helper')->track('feed', $showID);
        }

        // Make & return the response.
        $headers = [
            'Content-Type' => 'text/xml; charset=UTF-8',
            'Cache-Control' => 'private, max-age=0',
        ];

        return new Response($feedXml, 200, $headers);        
    }

    protected function xmlQuote($str)
    {
        return str_replace(['&', '<', '>', '"'], ['&amp;', '&lt;', '&gt;', '&quot;'], $str);
    }

    protected function xmlQuoteArray(array $arr, $exclude = [])
    {
        foreach ($arr as $k => $v) {
            if (!in_array($k, $exclude)) {
                $arr[$k] = $this->xmlQuote($v);
            }
        }

        return $arr;
    }

    public function generateFeed(array $show)
    {
        $helper = $this->app->make('podster/helper');

        // Quote the shows values for XML.
        $show = $this->xmlQuoteArray($show);

        // Get all of the shows episodes and quote them.
        $episodes = $this->app->make('podster/repositories/episodes')->all($show['id'])->fetchAll();
        foreach ($episodes as $k => $episode) {
            $episodes[$k] = $this->xmlQuoteArray($episode);
        }

        $showCoverUrl = $helper->getCoverUrl($show);

        $d = new DOMDocument('1.0', 'UTF-8');

        // Create the top-level <rss> element.
        $d->appendChild($rss = $d->createElement('rss'));
        $rss->setAttribute('version', '2.0');
        $rss->setAttribute('xmlns:content', 'http://purl.org/rss/1.0/modules/content/');
        $rss->setAttribute('xmlns:wfw', 'http://wellformedweb.org/CommentAPI/');
        $rss->setAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $rss->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');
        $rss->setAttribute('xmlns:sy', 'http://purl.org/rss/1.0/modules/syndication/');
        $rss->setAttribute('xmlns:slash', 'http://purl.org/rss/1.0/modules/slash/');
        $rss->setAttribute('xmlns:itunes', 'http://www.itunes.com/dtds/podcast-1.0.dtd');
        $rss->setAttribute('xmlns:rawvoice', 'http://www.rawvoice.com/rawvoiceRssModule/');
        $rss->setAttribute('xmlns:googleplay', 'http://www.google.com/schemas/play-podcasts/1.0');

        // Create the <channel> element.
        $rss->appendChild($channel = $d->createElement('channel'));
        $channel->appendChild($d->createElement('title', $show['title']));
        $channel->appendChild($d->createElement('link', $helper->getShowOrEpisodeLink($show)));
        $channel->appendChild($d->createElement('description', $show['description']));
        $channel->appendChild($d->createElement('lastBuildDate', (new DateTime())->format(DateTime::RFC2822)));
        $channel->appendChild($d->createElement('language', $show['language']));
        $channel->appendChild($d->createElement('managingEditor', $show['managingEditor']));
        $channel->appendChild($d->createElement('copyright', $show['copyright']));
        $channel->appendChild($atomLink = $d->createElement('atom:link'));
        $atomLink->setAttribute('href', $helper->getShowFeedUrl($show));
        $atomLink->setAttribute('rel', 'self');
        $atomLink->setAttribute('type', 'application/rss+xml');

        // Add the image element.
        $channel->appendChild($image = $d->createElement('image'));
        $image->appendChild($d->createElement('title', $show['title']));
        $image->appendChild($d->createElement('url', $showCoverUrl));
        $image->appendChild($d->createElement('link', $helper->getShowOrEpisodeLink($show)));

        // Update Frequency Tags.
        $channel->appendChild($d->createElement('sy:updatePeriod', 'hourly'));
        $channel->appendChild($d->createElement('sy:updateFrequency', '1'));

        // iTunes Tags.
        $channel->appendChild($d->createElement('itunes:subtitle', $show['subTitle']));
        $channel->appendChild($d->createElement('itunes:summary', $show['subTitle']));
        $channel->appendChild($d->createElement('itunes:author', $show['author']));
        $channel->appendChild($d->createElement('itunes:explicit', $helper->isShowExplicit($show['id']) ? 'yes' : 'no'));
        $channel->appendChild($d->createElement('itunes:keywords', $show['keywords']));

        // iTunes Image
        $channel->appendChild($itunesImage = $d->createElement('itunes:image'));
        $itunesImage->setAttribute('href', $showCoverUrl);

        // iTunes Owner Elements
        $channel->appendChild($owner = $d->createElement('itunes:owner'));
        $owner->appendChild($d->createElement('itunes:name', $show['ownerName']));
        $owner->appendChild($d->createElement('itunes:email', $show['ownerEmail']));

        // iTunes Categories
        $categories = $helper->commaToArray($show['categories']);
        foreach ($categories as $category) {
            $channel->appendChild($e = $d->createElement('itunes:category'));
            $e->setAttribute('text', $category);
        }

        // Add the generator info.
        $version = $helper->getPackage()->getPackageVersion();
        $comment = 'podcast_generator="Podster (c5labs)/' . $version . '" mode="advanced" feedslug="feed" ';
        $comment .= 'Podster Podcasting plugin for Concrete5 (https://c5labs.com/add-ons/podster)';
        $channel->appendChild($d->createComment($comment));

        // Add each episode.
        foreach ($episodes as $episode) {
            // Add the item.
            $channel->appendChild($item = $d->createElement('item'));
            $item->appendChild($guid = $d->createElement('guid', md5($episode['id'] . $show['id'])));
            $guid->setAttribute('isPermaLink', 'false');
            $item->appendChild($d->createElement('title', $episode['title']));
            $item->appendChild($d->createElement('link', $helper->getShowOrEpisodeLink($episode)));
            $item->appendChild($d->createElement('pubDate', (new DateTime($episode['pubDate']))->format(DateTime::RFC2822)));
            $item->appendChild($d->createElement('dc:creator', $show['ownerEmail']));
            $item->appendChild($d->createElement('description', strip_tags($episode['description'])));

            // Add the content element.
            $item->appendChild($contentEncoded = $d->createElement('content:encoded'));
            $contentEncoded->appendChild($d->createCDATASection($episode['description']));

            // Add the enclosure element.
            $mp3File = $helper->getAudioFile($episode);
            $item->appendChild($enclosure = $d->createElement('enclosure'));
            $enclosure->setAttribute('url', $helper->getEpisodeDownloadUrl($episode));
            $enclosure->setAttribute('type', 'audio/mpeg');
            $enclosure->setAttribute('length', $mp3File->getFullSize());

            // Add the categories.
            $categories = $helper->commaToArray($episode['categories']);
            foreach ($categories as $category) {
                $item->appendChild($c = $d->createElement('category'));
                $c->appendChild($d->createCDATASection($category));
            }

            // Add the iTunes relevant tags.
            $item->appendChild($d->createElement('itunes:subtitle', $episode['subTitle']));
            $item->appendChild($d->createElement('itunes:summary', $episode['subTitle']));
            $item->appendChild($d->createElement('itunes:author', $show['author']));
            $item->appendChild($d->createElement('itunes:explicit', $episode['explicit']));
            $item->appendChild($d->createElement('itunes:duration', $episode['duration']));
            $item->appendChild($d->createElement('itunes:keywords', $episode['keywords']));
        }

        return $d->saveXML();
    }
}