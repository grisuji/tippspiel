<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 15.01.15
 * Time: 20:16
 */
namespace Application\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Exception;

class TipTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function saveTip(Match $match)
    {
        $data = array(
            'userid' => $match->userid,
            'matchid' => $match->matchid,
            'team1tip' => $match->team1tip,
            'team2tip' => $match->team2tip
        );
        $id = (int)$match->tipid;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getTipById($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new Exception('Tip ID does not exist');
            }
        }
    }

    /**
     * Get Tips by id
     * @param string $id
     * @throws Exception
     * @return array|\ArrayObject|null
     */
    public function getTipById($id)
    {
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new Exception("Could not find tip with id $id");
        }
        return $row;
    }

    /**
     * Get Tips by matchid and userid
     * @param string $matchid
     * @param string $userid
     * @throws Exception
     * @return array|\ArrayObject|null
     */
    public function getUserTipByMatch($matchid, $userid)
    {
        $rowset = $this->tableGateway->select(array('matchid' => $matchid, 'userid' => $userid));
        $row = $rowset->current();
        if (!$row) {
            throw new Exception("Could not find tip for match $matchid from user $userid");
        }
        return $row;
    }

}