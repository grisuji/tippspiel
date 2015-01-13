<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;

return array(
    'db' => array(
        'driver'            => 'Pdo',
        'dsn'               => 'mysql:dbname=tippspiel_db;host=localhost',
        'username'          => 'grisuji',
        'password'          => 'dgLieQuwsndK_1970', // das ganze Leben ist ein Quiz ....
        'driver_options'    => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'Zend\Authentication\AuthenticationService' => 'AuthService',
        ),
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            // SERVICES
            'AuthService' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'user','name','password', 'MD5(?)');

                $authService = new AuthenticationService();
                $authService->setAdapter($dbTableAuthAdapter);
                return $authService;
            },
        ),
    ),
);
