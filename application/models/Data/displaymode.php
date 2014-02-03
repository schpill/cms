<?php
    return array(
        'fields'                    => array(
            'name'                  => array(
                'label'             => 'Nom'
            )
        ),
        'settings'                  => array(
            'relationships'         => array(
                'pages'             => array(
                    'type'          => 'manyToMany',
                    'onDelete'      => 'cascade'
                ),
            ),
            'singular'              => 'Type de page',
            'plural'                => 'Types de page',
            'orderList'             => 'name',
            'checkTuple'            => 'name',
            'orderListDirection'    => 'ASC',
        ),
    );
