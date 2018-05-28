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
abstract class BaseRepository
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    abstract public function getTableName();

    public function constrain(array $constraints, array $order = [], $select = '*')
    {
        $constraints = array_values($constraints);

        if (empty($order)) {
            $order = ['column' => 'id', 'direction' => 'ASC'];
        }

        $q = 'SELECT ' . $select . ' FROM ' . $this->getTableName() . ' ';

        if (count($constraints) > 0) {
            $q .= 'WHERE ';
        }

        $params = [];

        foreach ($constraints as $i => $constraint) {
            if ($i > 0 && !isset($constraint['condition'])) {
                $constraint['condition'] = 'AND';
            }

            array_push($params, $constraint['value']);

            $constraint['value'] = '?';

            if (isset($constraint['condition'])) {
                $q .= ' ' . $constraint['condition'] . ' ';
            }

            unset($constraint['condition']);

            $q .= implode(' ', $constraint) . ' ';
        }

        $q .= ' ORDER BY ? ?';

        array_push($params, $order['column'], $order['direction']);

        return $this->db->query($q, $params);
    }
}