<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Application\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\ResultInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use DateTime;
use DateInterval;
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
    public function getUserMatchesByDay($saison, $userid, $day)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'league', 'saison', 'date_time', 'groupid', 'team1goals', 'team2goals', 'isfinished' ));
        $select->join(array('team1' => 'teams'), 'team1.id=matches.team1id', array('team1name' => 'longname', 'team1emblem' => 'emblem'), 'left');
        $select->join(array('team2' => 'teams'), 'team2.id=matches.team2id', array('team2name' => 'longname', 'team2emblem' => 'emblem'),'left');
        $expression = new Expression('matchid=matches.id AND userid='.$userid);
        $select->join('tips', $expression, array('tipid'=>'id', 'userid', 'team1tip', 'team2tip'), $select::JOIN_LEFT);
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

    public function getSaisonTipsAndMatches($saison)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'league', 'saison', 'groupid', 'date_time', 'team1goals', 'team2goals', 'isfinished',  ));
        $select->join('tips', 'matchid=matches.id', array('tipid'=>'id', 'userid', 'team1tip', 'team2tip'), 'left');
        $select->join(array('team1' => 'teams'), 'team1.id=matches.team1id', array('team1name' => 'longname', 'team1emblem' => 'emblem'), 'left');
        $select->join(array('team2' => 'teams'), 'team2.id=matches.team2id', array('team2name' => 'longname', 'team2emblem' => 'emblem'),'left');
        $select->join('user', 'user.id=tips.userid', array('username' => 'name'),'left');
        $select->order('date_time ASC');
        $where = new Where();
        $where->equalTo('saison',(int) $saison)
            ->AND
            ->isNotNull('userid');
        $select->where($where);
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

        return "1";
    }
}