<?php
namespace Rest;
use Rest\Model\RawTodddeMatchMappingTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Rest\Model\RawMatch;
use Rest\Model\RawMatchTable;
use Rest\Model\RawTip;
use Rest\Model\RawTipTable;
use Rest\Model\RawTeam;
use Rest\Model\RawTeamTable;
use Rest\Model\RawUser;
use Rest\Model\RawUserTable;
use Rest\Model\RawTodddeTip;
use Rest\Model\RawTodddeTipTable;
use Rest\Model\RawTodddeTeamMapping;
use Rest\Model\RawTodddeTeamMappingTable;
use Rest\Model\RawTodddeUserMapping;
use Rest\Model\RawTodddeUserMappingTable;


class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'RawMatchTable' => function ($sm) {
                    $tableGateway = $sm->get('RawMatchTableGateway');
                    $table = new RawMatchTable($tableGateway);
                    return $table;
                },
                'RawMatchTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RawMatch());
                    return new TableGateway('matches', $dbAdapter, null, $resultSetPrototype);
                },
                'RawTipTable' => function ($sm) {
                    $tableGateway = $sm->get('RawTipTableGateway');
                    $table = new RawTipTable($tableGateway);
                    return $table;
                },
                'RawTipTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RawTip());
                    return new TableGateway('tips', $dbAdapter, null, $resultSetPrototype);
                },
                'RawTeamTable' => function ($sm) {
                    $tableGateway = $sm->get('RawTeamTableGateway');
                    $table = new RawTeamTable($tableGateway);
                    return $table;
                },
                'RawTeamTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RawTeam());
                    return new TableGateway('teams', $dbAdapter, null, $resultSetPrototype);
                },
                'RawUserTable' => function ($sm) {
                    $tableGateway = $sm->get('RawUserTableGateway');
                    $table = new RawUserTable($tableGateway);
                    return $table;
                },
                'RawUserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RawUser());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'RawTodddeTeamMappingTable' => function ($sm) {
                    $tableGateway = $sm->get('RawTodddeTeamMappingTableGateway');
                    $table = new RawTodddeTeamMappingTable($tableGateway);
                    return $table;
                },
                'RawTodddeTeamMappingTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RawTodddeTeamMapping());
                    return new TableGateway('toddde_team_matching', $dbAdapter, null, $resultSetPrototype);
                },
                'RawTodddeUserMappingTable' => function ($sm) {
                    $tableGateway = $sm->get('RawTodddeUserMappingTableGateway');
                    $table = new RawTodddeUserMappingTable($tableGateway);
                    return $table;
                },
                'RawTodddeUserMappingTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RawTodddeUserMapping());
                    return new TableGateway('toddde_user_matching', $dbAdapter, null, $resultSetPrototype);
                },
                'RawTodddeTipTable' => function ($sm) {
                    $tableGateway = $sm->get('RawTodddeTipTableGateway');
                    $table = new RawTodddeTipTable($tableGateway);
                    return $table;
                },
                'RawTodddeTipTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RawTodddeTip());
                    return new TableGateway('todddetips', $dbAdapter, null, $resultSetPrototype);
                },
            ),
            'invokables' => array(),
            'services' => array(),
            'shared' => array(),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}