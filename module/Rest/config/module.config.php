<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Rest\Controller\UserRest' => 'Rest\Controller\UserRestController',
            'Rest\Controller\MatchRest' => 'Rest\Controller\MatchRestController',
            'Rest\Controller\TipRest' => 'Rest\Controller\TipRestController',
            'Rest\Controller\TeamRest' => 'Rest\Controller\TeamRestController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rest\Controller\UserRest',
                    ),
                ),
            ),'match-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/match-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rest\Controller\MatchRest',
                    ),
                ),
            ),'tip-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/tip-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rest\Controller\TipRest',
                    ),
                ),
            ),'team-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/team-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rest\Controller\TeamRest',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy'
        )
    )
);