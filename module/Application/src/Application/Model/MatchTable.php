<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Application\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Exception;


class MatchTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Get all matches of a given day in saison
     * @param string $day
     * @throws Exception
     * @return array|\ArrayObject|null
     */
    public function getMatchesByDate($day)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'matchid', 'team1goals', 'team2goals' ));
        $select->join(array('team1' => 'teams'), 'team1.id=matches.team1id', array('team1name' =>'longname'), 'right');
        $select->join(array('team2' => 'teams'), 'team2.id=matches.team2id', array('team2name' => 'longname'),'right');
        $select->where(array('groupid' => (int) $day));
        $resultset = $this->tableGateway->selectWith($select);
        return $resultset;
    }


}