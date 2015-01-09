<?php
namespace Users;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Users\Model\UserTable;
use Users\Model\User;

use Users\Form\LoginFilter;
use Users\Form\LoginForm;
use Users\Form\UserEditFilter;
use Users\Form\UserEditForm;
use Users\Form\RegisterFilter;
use Users\Form\RegisterForm;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
            'aliases' => array(),
            'factories' => array(
                'UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user',$dbAdapter,null, $resultSetPrototype);
                },

                //FORMS
                'LoginForm' => function ($sm) {
                    $form = new LoginForm();
                    $form->setInputFilter($sm->get('LoginFilter'));
                    return $form;
                },
                'UserEditForm' => function ($sm) {
                    $form = new UserEditForm();
                    $form->setInputFilter($sm->get('UserEditFilter'));
                    return $form;
                },
                'RegisterForm' => function ($sm) {
                    $form = new RegisterForm();
                    $form->setInputFilter($sm->get('RegisterFilter'));
                    return $form;
                },

                //FILTERS
                'LoginFilter' => function ($sm) {
                    $filter = new LoginFilter();
                    return $filter;
                },
                'UserEditFilter' => function ($sm) {
                    $filter = new UserEditFilter();
                    return $filter;
                },
                'RegisterFilter' => function ($sm) {
                    $filter = new RegisterFilter();
                    return $filter;
                },
            ),
            'invokables' => array(),
            'services' => array(),
            'shared' => array(),
        );
    }
}
