<?php
namespace Rest;
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