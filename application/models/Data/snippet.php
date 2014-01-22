<?php
    return array(
        /* Les champs */
        'fields'                => array(
            'name'              => array(
                'label'         => 'Nom',
            ),
            'code'             => array(
                'label'         => 'Contenu',
                'type'          => 'textarea',
                'isTranslated'  => true,
                'canBeNull'     => true,
                'notExportable' => true,
                'noList'        => true
            ),
        ),
        /* les parametres */
        'settings'              => array(
            /* les indexes */
            'indexes'     => array(

            ),
            /* les relations */
            'relationships'     => array(
            ),
            'versioning'         => true,
            'checkTuple'         => 'name', 'collection',
            'orderList'          => 'name',
            'orderListDirection' => 'ASC'
        ),
    );
