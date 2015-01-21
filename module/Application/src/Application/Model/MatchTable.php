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
use Zend\Debug\Debug;


class MatchTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Get all matches of a given day in saison
     * @param integer $day
     * @throws Exception
     * @return array|\ArrayObject|null
     */
    public function getMatchesByDay($day)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'league', 'saison', 'date_time', 'groupdid', 'team1goals', 'team2goals', 'isfinished' ));
        $select->join(array('team1' => 'teams'), 'team1.id=matches.team1id',
                      array('team1name' => 'longname', 'team1emblem' => 'emblem'), 'left');
        $select->join(array('team2' => 'teams'), 'team2.id=matches.team2id',
                      array('team2name' => 'longname', 'team2emblem' => 'emblem'),'left');
        $select->where(array('groupid' => (int) $day));
        $resultset = $this->tableGateway->selectWith($select);
        return $resultset;
    }

    /**
     * Get all matches + tips of an user at a given day in saison
     * @param integer $userid
     * @param integer $day
     * @throws Exception
     * @return array|\ArrayObject|null
     */
    public function getUserMatchesByDay($userid, $day)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'league', 'saison', 'date_time', 'groupid', 'team1goals', 'team2goals', 'isfinished' ));
        $select->join(array('team1' => 'teams'), 'team1.id=matches.team1id', array('team1name' =>'longname'), 'left');
        $select->join(array('team2' => 'teams'), 'team2.id=matches.team2id', array('team2name' => 'longname'),'left');
        $select->join('tips', 'matchid=matches.id', array('tipid'=>'id', 'userid', 'team1tip', 'team2tip'), 'left');
        $where = new Where();
        $where->equalTo('groupid',(int) $day)
            ->AND
            ->NEST
            ->equalTo('userid', (int) $userid)
            ->OR
            ->isNull('userid');
        $select->where($where);
        $select->order('id');
        $resultset = $this->tableGateway->selectWith($select);
        #Debug::Dump($select->getSqlString());
        #foreach($resultset as $row) {
        #    Debug::Dump($row);
        #}
        $rs = new ResultSet();
        $rs->initialize($resultset);
        $rs->buffer();
        return $rs;
    }

}