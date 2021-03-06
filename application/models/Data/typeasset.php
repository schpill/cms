<?php
    return array(
        'fields' => array(
            'name'    => array('label' => 'Nom')
        ),
        'settings' => array(
            'relationships' => array(
                'assets'    => array('type' => 'manyToMany', 'onDelete' => 'cascade'),
            ),
            'singular'              => 'Type d\'asset',
            'plural'                => 'Types d\'asset',
            'orderList'             => 'name',
            'checkTuple'            => 'name',
            'orderListDirection'    => 'ASC',
        ),
    );
