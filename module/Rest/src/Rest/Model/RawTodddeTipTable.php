<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 16.01.15
 * Time: 14:49
 */

namespace Rest\Model;

use Zend\Db\ResultSet\ResultInterface;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;


class RawTodddeTipTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }


    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getNewTodddeTip($timestamp){
        $date = date("Y-m-d H:i:s", $timestamp);
        $select = $this->tableGateway->getSql()->select();
        $where = new Where();
        $where->greaterThan('lastchange',$date);
        $select->where($where);
        $resultset = $this->tableGateway->selectWith($select);
        return $resultset;
    }

}