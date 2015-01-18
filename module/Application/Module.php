<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Application\Model\Match;
use Application\Model\MatchTable;
use Application\Model\Tip;
use Application\Model\TipTable;

use Application\Form\DeliverForm;
use Application\Form\DeliverFilter;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
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
                //FORMS
                'DeliverForm' => function ($sm) {
                    $form = new DeliverForm();
                    $form->setInputFilter($sm->get('DeliverFilter'));
                    return $form;
                },
                //FILTERS
                'DeliverFilter' => function ($sm) {
                    $filter = new DeliverFilter();
                    return $filter;
                },
                //TABLES
                'MatchTable' => function($sm)
                {
                    $tableGateway = $sm->get('MatchTableGateway');
                    $table = new MatchTable($tableGateway);
                    return $table;
                },
                'MatchTableGateway' => function($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Match());
                    return new TableGateway('matches',$dbAdapter,null, $resultSetPrototype);
                },
                'TipTable' => function($sm)
                {
                    $tableGateway = $sm->get('TipTableGateway');
                    $table = new TipTable($tableGateway);
                    return $table;
                },
                'TipTableGateway' => function($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tip());
                    return new TableGateway('tips',$dbAdapter,null, $resultSetPrototype);
                },
            )
        );
    }
}
