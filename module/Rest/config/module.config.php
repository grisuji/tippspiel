<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Rest\Controller\UserRest' => 'Rest\Controller\UserRestController',
            'Rest\Controller\MatchRest' => 'Rest\Controller\MatchRestController',
            'Rest\Controller\TipRest' => 'Rest\Controller\TipRestController',
            'Rest\Controller\TeamRest' => 'Rest\Controller\TeamRestController',
            'Rest\Controller\TodddeTeamRest' => 'Rest\Controller\TodddeTeamMappingRestController',
            'Rest\Controller\TodddeUserRest' => 'Rest\Controller\TodddeUserMappingRestController',
            'Rest\Controller\TodddeTipRest' => 'Rest\Controller\TodddeTipRestController',
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
            ),'toddde-team-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/toddde-team-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rest\Controller\TodddeTeamRest',
                    ),
                ),
            ),'toddde-user-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/toddde-user-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rest\Controller\TodddeUserRest',
                    ),
                ),
            ),'toddde-tip-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/toddde-tip-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rest\Controller\TodddeTipRest',
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