<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 15.01.15
 * Time: 20:16
 */
namespace Rest\Model;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use Zend\Debug\Debug;

class RawTipTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        return $this->getNewTips(0);
    }

    public function getNewTips($timestamp){
        $now = date("Y-m-d H:i:s");
        $last = date("Y-m-d H:i:s",$timestamp);
        $select = new Select();
        $select->columns(array('id', 'userid','matchid', 'team1tip', 'team2tip','lastchange' ));
        $select->from(array('t'=>'tips'));
        $select->join(array('m'=>'matches'), 't.matchid = m.id',array('mid' => 'id'),'left');
        $where = new Where();
        //$where->greaterThan('lastchange',$last);
        $where->lessThan('m.date_time',$now)
            ->AND
            ->greaterThanOrEqualTo('t.lastchange', $last);
        $select->where($where);
        #print_r($select->getSqlString());
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

}