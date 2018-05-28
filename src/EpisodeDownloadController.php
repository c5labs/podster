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
use Concrete\Core\Http\Response;
use Concrete\Core\Routing\Redirect;
use DOMDocument;
use DateTime;
use Route;

/**
 * Package Controller Class.
 *
 * Run a podcast from concrete5.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
class EpisodeDownloadController extends Controller
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function registerRoute()
    {
        Route::register('/podcasts/{showID}/episodes/{episodeID}/{filename}', [$this, 'handle']);
    }

    public function handle($showID, $episodeID, $filename)
    {
        $episode = $this->app->make('podster/repositories/episodes')->find($episodeID);

        if (!$episode || $showID !== $episode['showID']) {
            return new Response('Not found.', 404);
        }

        $url = $this->app->make('podster/helper')->getAudioFile($episode)->getUrl();

        $this->app->make('podster/helper')->track('episode', $showID, $episodeID);

        return Redirect::url($url, 302);
    }
}
