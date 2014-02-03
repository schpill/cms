<?php
    return array(
        /* Les champs */
        'fields'                => array(
            'entity'            => array(
                'label'         => 'Entité',
            ),
            'fields'            => array(
                'label'         => 'Champs',
                'type'          => 'code',
                'notSearchable' => true,
                'noList'        => true
            ),
            'settings'          => array(
                'label'         => 'Paramètres',
                'type'          => 'code',
                'notSearchable' => true,
                'noList'        => true
            ),
        ),
        /* les parametres */
        'settings'               => array(
            /* les hooks */
            /* les indexes */
            'indexes'            => array(
            ),
            /* les relations */
            'relationships'      => array(
            ),
            'singular'           => 'Type de contenu personnalisé',
            'plural'             => 'Types de contenu personnalisés',
            'checkTuple'         => 'entity',
            'orderList'          => 'entity',
            'orderListDirection' => 'ASC'
        ),
    );
