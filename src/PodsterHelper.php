<?php
/**
 * Podster Package Controller File.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
namespace Concrete\Package\Podster\Src;

use File;
use DateTime;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * Package Controller Class.
 *
 * Run a podcast from concrete5.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
class PodsterHelper
{
    public $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function track($type, $showID, $episodeID = null)
    {
        $this->app->make('podster/repositories/stats')->create([
            'type' => $type,
            'showID' => $showID,
            'episodeID' => $episodeID,
            'createdAt' => (new DateTime())->format(DateTime::ATOM),
            'userAgent' => $_SERVER['HTTP_USER_AGENT'],
            'remoteAddress' => $_SERVER['REMOTE_ADDR'],
        ]);
    }

    public function getAudioFile(array $show)
    {
        if (isset($show['mp3FileID']) && $show['mp3FileID'] > 0) {
            $file = File::getById($show['mp3FileID']);
            return $file->getRecentVersion();
        }
    }

    public function getTranscriptFile(array $show)
    {
        if (isset($show['transcriptFileID']) && $show['transcriptFileID'] > 0) {
            $file = File::getById($show['transcriptFileID']);
            return $file->getRecentVersion();
        }
    }

    public function getCoverFile(array $show)
    {
        if (isset($show['coverFileID']) && $show['coverFileID'] > 0) {
            $file = File::getById($show['coverFileID']);
            return $file->getRecentVersion();
        }
    }

    public function getCoverUrl(array $show)
    {
        if ($file = $this->getCoverFile($show)) {
            return $file->getUrl();
        }
    }

    public function getCoverCss(array $show)
    {
        $url = $this->getCoverUrl($show);

        if ($url) {
            return 'background: url(' . $url . ');';
        }

        return '';
    }

    public function getShowFeedUrl($show)
    {
        return \View::url('/podcasts', $show['id']);
    }

    public function getEpisodeDownloadUrl($episode)
    {
        $file = $this->getAudioFile($episode);

        return \View::url('/podcasts', $episode['showID'], 'episodes', $episode['id'], $file->getFilename());
    }

    public function getShowOrEpisodeLink(array $data)
    {
        if (isset($data['linkType'])) {
            if ('page' === $data['linkType']) {
                $path = \Page::getById($data['linkCID'])->getCollectionPath();

                if (empty($path)) {
                    $path = '/';
                }

                return \View::url($path);
            }

            return $data['linkUrl'];
        }
    }

    public function validateNotEmpty(array $keys, array $data)
    {
        $errors = [];

        foreach ($keys as $key) {
            if (!$this->app->make('helper/validation/strings')->notempty($data[$key])) {
                $errors[$key] = sprintf('The %s must not be empty.', $key);
            }
        }

        return $errors;
    }

    public function getLanguages()
    {
        $path = realpath(__DIR__ . '/../assets/languages.json');

        $json = file_get_contents($path);

        return json_decode($json, true);
    }

    public function getCategories()
    {
        $path = realpath(__DIR__ . '/../assets/categories.json');

        $json = file_get_contents($path);

        return json_decode($json, true);
    }

    public function getPackage()
    {
        return \Package::getByHandle('podster-package');
    }

    public function isShowExplicit($showId)
    {
        $result = $this->app->make('podster/repositories/episodes')->constrain([
            [
                'column' => 'showID',
                'operator' => '=',
                'value' => $showId,
            ],
            [
                'condition' => 'AND',
                'column' => 'explicit',
                'operator' => '<>',
                'value' => 'No',
            ]
        ], [], 'COUNT(id) as count');

        return intval($result->fetchRow()['count']) > 0 ? true : false;
    }

    public function commaToArray($list, $clean = true)
    {
        $list = explode(',', $list);

        if ($clean) {
            $list = array_map(function($item) {
                return trim($item);
            }, $list);

            $list = array_filter($list, function($item) {
                return !empty($item);
            });
        }

        return $list;
    }
}