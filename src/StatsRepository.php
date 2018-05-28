<?php
/**
 * Podster Package Controller File.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
namespace Concrete\Package\Podster\Src;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * Package Controller Class.
 *
 * Run a podcast from concrete5.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
class StatsRepository extends BaseRepository
{
    public function getTableName()
    {
        return 'podsterStats';
    }

    public function create(array $data)
    {
        $q = 'INSERT INTO podsterStats (type, showID, episodeID, createdAt, userAgent, remoteAddress) VALUES (?,?,?,?,?,?)';

        return $this->db->query($q, array_values($data));
    }

    public function getShowDownloads($show)
    {
        $result = $this->constrain([
            [
                'column' => 'showID',
                'operator' => '=',
                'value' => $show['id'],
            ],
            [
                'column' => 'type',
                'operator' => '=',
                'value' => 'episode',
            ],
        ], [], 'COUNT(type) as count')->fetchRow();

        return $result['count'];
    }

    public function getFeedViews($show)
    {
        $result = $this->constrain([
            [
                'column' => 'showID',
                'operator' => '=',
                'value' => $show['id'],
            ],
            [
                'column' => 'type',
                'operator' => '=',
                'value' => 'feed',
            ],
        ], [], 'COUNT(type) as count')->fetchRow();

        return $result['count'];
    }

    public function getEpisodeDownloads($episode)
    {
        $constraints = [
            [
                'column' => 'episodeID',
                'operator' => '=',
                'value' => $episode['id'],
            ],
            [
                'column' => 'showID',
                'operator' => '=',
                'value' => $episode['showID'],
            ],
            [
                'column' => 'type',
                'operator' => '=',
                'value' => 'episode',
            ],
        ];

        $result = $this->constrain($constraints, [], 'COUNT(type) as count')->fetchRow();

        return $result['count'];
    }
}