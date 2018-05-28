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
class EpisodeRepository extends BaseRepository
{
    public function getTableName()
    {
        return 'podsterEpisodes';
    }

    protected function preProcessAttributes(array $attr)
    {
        return [
            'title' => $attr['title'],
            'subTitle' => empty($attr['subTitle']) ? substr(strip_tags($attr['description']), 0, 64) . '...' : $attr['subTitle'],
            'description' => $attr['description'],
            'linkType' => empty($attr['linkType']) ? 'page' : $attr['linkType'],
            'linkCID' => empty($attr['linkCID']) ? 1 : $attr['linkCID'],
            'linkUrl' => empty($attr['linkUrl']) ? null : $attr['linkUrl'],
            'categories' => empty($attr['categories']) ? null : $attr['categories'],
            'keywords' => empty($attr['keywords']) ? null : $attr['keywords'],
            'duration' => $attr['duration'],
            'explicit' => empty($attr['explicit']) ? 'No' : $attr['explicit'],
            'coverFileID' => empty($attr['coverFileID']) ? null : $attr['coverFileID'],
            'transcriptFileID' => empty($attr['transcriptFileID']) ? null : $attr['transcriptFileID'],
            'mp3FileID' => empty($attr['mp3FileID']) ? null : $attr['mp3FileID'],
            'showID' => $attr['showID'],
            'pubDate' => (new \DateTime())->format(\DateTime::ATOM),
        ];
    }

    public function create(array $attr)
    {
        $q = 'INSERT INTO podsterEpisodes (title, subTitle, description, linkType, linkCID, linkUrl, categories, keywords, duration, explicit, coverFileID, transcriptFileID, mp3FileID, showId, pubDate) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        return $this->db->query($q, array_values($this->preProcessAttributes($attr)));
    }

    public function update($id, array $attr)
    {
        $q = 'UPDATE podsterEpisodes SET title=?, subTitle=?, description=?, linkType=?, linkCID=?, linkUrl=?, categories=?, keywords=?, duration=?, explicit=?, coverFileID=?, transcriptFileID=?, mp3FileID=?, showId=? WHERE id = ?';

        $attr = array_values(
            $this->preProcessAttributes($attr)
        );

        // Remove the pubDate field.
        array_pop($attr);

        array_push($attr, $id);

        return $this->db->query($q, $attr);
    }
    
    public function all($showId = null)
    {
        $constraints = [];

        if (!empty($showId)) {
            $constraints[] = [
                'column' => 'showID',
                'operator' => '=',
                'value' => $showId,
            ];
        }

        return $this->constrain($constraints);
    }

    public function last($showID)
    {
        $q = 'SELECT * FROM podsterEpisodes WHERE showID = ? ORDER BY id DESC LIMIT 0,1';
        
        return $this->db->query($q, [$showID])->fetchRow();
    }

    public function find($id)
    {
        $q = 'SELECT * FROM podsterEpisodes WHERE id = ?';
        
        return $this->db->query($q, [$id])->fetchRow();
    }

    public function delete($id)
    {
        if ($show = $this->find($id)) {
            $q = 'DELETE FROM podsterEpisodes WHERE id = ?';
        
            $this->db->query($q, [$id]);
        }

        return true;
    }
}