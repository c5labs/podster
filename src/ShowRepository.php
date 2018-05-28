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
class ShowRepository extends BaseRepository
{
    public function getTableName()
    {
        return 'podsterShows';
    }

    protected function preProcessAttributes(array $attr)
    {
        $currentUserEmail = (new \Concrete\Core\User\User())->getUserInfoObject()->getUserEmail();

        return [
            'title' => $attr['title'],
            'subTitle' => empty($attr['subTitle']) ? substr($attr['description'], 0, 64) . '...' : $attr['subTitle'],
            'description' => $attr['description'],
            'linkType' => empty($attr['linkType']) ? 'page' : $attr['linkType'],
            'linkCID' => empty($attr['linkCID']) ? 1 : $attr['linkCID'],
            'linkUrl' => empty($attr['linkUrl']) ? null : $attr['linkUrl'],
            'author' => $attr['author'],
            'copyright' => empty($attr['copyright']) ? '&copy; ' . date('Y') . ' | ' . $attr['author'] : $attr['copyright'],
            'ownerName' => empty($attr['ownerName']) ? $attr['author'] : $attr['ownerName'],
            'ownerEmail' => empty($attr['ownerEmail']) ? $currentUserEmail : $attr['ownerEmail'],
            'managingEditor' => empty($attr['managingEditor']) ? $attr['author'] : $attr['managingEditor'],
            'categories' => $attr['categories'],
            'keywords' => empty($attr['keywords']) ? $attr['categories'] : $attr['keywords'],
            'language' => empty($attr['language']) ? 'en-us' : $attr['language'],
            'coverFileID' => empty($attr['coverFileID']) ? null : $attr['coverFileID'],
        ];
    }

    public function create(array $attr)
    {
        $q = 'INSERT INTO podsterShows (title, subTitle, description, linkType, linkCID, linkUrl, author, copyright, ownerName, ownerEmail, managingEditor, categories, keywords, language, coverFileID) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        return $this->db->query($q, array_values($this->preProcessAttributes($attr)));
    }

    public function update($id, array $attr)
    {
        $q = 'UPDATE podsterShows SET title=?, subTitle=?, description=?, linkType=?, linkCID=?, linkUrl=?, author=?, copyright=?, ownerName=?, ownerEmail=?, managingEditor=?, categories=?, keywords=?, language=?, coverFileID=? WHERE id = ?';

        $attr = array_values(
            $this->preProcessAttributes($attr)
        );

        array_push($attr, $id);

        return $this->db->query($q, $attr);
    }

    public function all()
    {
        $subQuery = 'SELECT COUNT(*) FROM podsterEpisodes WHERE podsterEpisodes.showID = podsterShows.id';
        $q = 'SELECT *, (' . $subQuery . ') as numEpisodes FROM podsterShows ORDER BY id DESC';
        return $this->db->query($q, []);
    }

    public function find($id)
    {
        $q = 'SELECT * FROM podsterShows WHERE id = ?';
        
        return $this->db->query($q, [$id])->fetchRow();
    }

    public function delete($id)
    {
        if ($show = $this->find($id)) {
            $q = 'DELETE FROM podsterShows WHERE id = ?';
        
            $this->db->query($q, [$id]);
        }

        return true;
    }
}