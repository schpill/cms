<?php
    return array(
        'fields' => array(
            'name'    => array('label' => 'Nom')
        ),
        'settings' => array(
            'relationships' => array(
                'assets'    => array('type' => 'manyToMany', 'onDelete' => 'cascade'),
            ),
            'orderList'             => 'name',
            'checkTuple'            => 'name',
            'orderListDirection'    => 'ASC',
        ),
    );
