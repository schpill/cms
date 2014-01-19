<?php
    return array(
        'fields' => array(
            'name'              => array('label' => 'Nom'),
            'abbreviation'      => array(
                'label'         => 'Abbreviation',
                'checkValue'    => function ($val) {
                    if (strlen($val) > 2) {
                        throw new \Exception("You must provide an abbreviation with 2 characters max length.");
                    }
                    return $val;
                },
            ),
        ),
        'settings' => array(
            'orderList'             => 'name',
            'checkTuple'            => 'Abbreviation',
            'orderListDirection'    => 'ASC',
        ),
    );
