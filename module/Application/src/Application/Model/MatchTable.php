<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Application\Model;

use Zend\Db\Metadata\Source\OracleMetadata;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\ResultInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use DateTime;
use DateInterval;
use Exception;
use Zend\Debug\Debug;
use Zend\Ldap\Filter\AndFilter;


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
        $select->columns(array('id', 'league', 'saison', 'date_time', 'groupdid', 'team1goals', 'team2goals', 'team1halfgoals', 'team2halfgoals', 'isfinished' ));
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
     * @param string $sourcetable for tips (default=tips)
     * @throws Exception
     * @return array|\ArrayObject|null
     */
    public function getUserMatchesByDay($saison, $userid, $day, $sourcetable="tips")
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'league', 'saison', 'date_time', 'groupid', 'team1goals', 'team2goals', 'team1halfgoals', 'team2halfgoals', 'isfinished' ));
        $select->join(array('team1' => 'teams'), 'team1.id=matches.team1id', array('team1name' => 'longname', 'team1emblem' => 'emblem'), 'left');
        $select->join(array('team2' => 'teams'), 'team2.id=matches.team2id', array('team2name' => 'longname', 'team2emblem' => 'emblem'),'left');
        $expression = new Expression('matchid=matches.id AND userid='.$userid);
        $select->join(array('tips' => $sourcetable), $expression, array('tipid'=>'id', 'userid', 'team1tip', 'team2tip'), $select::JOIN_LEFT);
        $where = new Where();
        $where->equalTo('groupid',(int) $day)
            ->AND
            ->equalTo('saison', $saison)
            ->AND
            ->NEST
            ->equalTo('userid', (int) $userid)
            ->OR
            ->isNull('userid');
        $select->where($where);
        $select->order('date_time, id');
        $resultset = $this->tableGateway->selectWith($select);
        $rs = new ResultSet();
        $rs->initialize($resultset);
        $rs->buffer();
        return $rs;
    }

    public function getSaisonTipsAndMatches($saison, $sourcetable)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'league', 'saison', 'groupid', 'date_time', 'team1goals', 'team2goals', 'team1halfgoals', 'team2halfgoals', 'isfinished',  ));
        $select->join(array('tips' => $sourcetable), 'matchid=matches.id', array('tipid'=>'id', 'userid', 'team1tip', 'team2tip'), 'right');
        $select->join(array('team1' => 'teams'), 'team1.id=matches.team1id', array('team1name' => 'longname', 'team1emblem' => 'emblem'), 'left');
        $select->join(array('team2' => 'teams'), 'team2.id=matches.team2id', array('team2name' => 'longname', 'team2emblem' => 'emblem'),'left');
        $select->join('user', 'user.id=tips.userid', array('username' => 'name', 'lastlogin' => 'lastlogin'),'left');
        $select->order('date_time ASC');
        $where = new Where();
        $where->equalTo('saison',(int) $saison)
              ->AND
              ->NEST
              ->notEqualTo('lastlogin', "1970-01-01 00:00:00")
              ->OR
              ->isNull('lastlogin');
        $select->where($where);
        //Debug::dump($select->getSqlString());
        $resultset = $this->tableGateway->selectWith($select);
        $rs = new ResultSet();
        $rs->initialize($resultset);
        $rs->buffer();
        return $rs;
    }

    /**
     * checks the day of the next unfinished match.
     * If it more than 24h in the future, the day-1
     * is given.
     * @return int
     */
    public function getDayOfNextMatch()
    {
        /* @var $match \Application\Model\Match  */
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('date_time', 'groupid', 'isfinished' ));
        $select->where(array('isfinished' => 0));
        $select->order('date_time');
        $select->limit(1);

        $result = $this->tableGateway->selectWith($select);
        $match = $result->current();
        if ($match) {
            $start = new DateTime($match->start);
            $border = new DateTime();
            $border->add(new DateInterval("P1D"));
            if ($border->getTimestamp() < $start->getTimestamp() and $match->day > 1) {
                return $match->day-1;
            }
            return $match->day;
        }

        return $this->getDayOfLastMatch();
    }

    /**
     * checks the day of the last finished match.
     * @return int
     */
    public function getDayOfLastMatch()
    {
        /* @var $match \Application\Model\Match  */
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('date_time', 'groupid', 'isfinished' ));
        $select->where(array('isfinished' => 1));
        $select->order('date_time DESC');
        $select->limit(1);

        $result = $this->tableGateway->selectWith($select);
        $match = $result->current();
        if ($match) {
            $start = new DateTime($match->start);
            $border = new DateTime();
            $border->add(new DateInterval("P1D"));
            return $match->day;
        }

        return "1";
    }

    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getNewMatches($timestamp){
        $date = date("Y-m-d H:i:s", $timestamp);
        $select = $this->tableGateway->getSql()->select();
        $where = new Where();
        $where->greaterThan('lastchange',$date);
        $select->where($where);
        $resultset = $this->tableGateway->selectWith($select);
        return $resultset;
    }

}