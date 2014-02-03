<?php
    return array(
        'fields' => array(
            'name'    => array('label' => 'Nom')
        ),
        'settings' => array(
            'relationships' => array(
                'admintasks'    => array('type' => 'manyToMany', 'onDelete' => 'cascade'),
            ),
            'singular'              => 'Statut de tâche',
            'plural'                => 'Statuts de tâche',
            'orderList'             => 'name',
            'checkTuple'            => 'name',
            'orderListDirection'    => 'ASC',
        ),
    );
