<?php
    return array(
        /* Les champs */
        'fields'                    => array(
            'name'                  => array(
                'label'             => 'Nom',
            ),
            'html'                  => array(
                'label'             => 'Contenu',
                'type'              => 'editor',
                'isTranslated'      => true,
                'canBeNull'         => true,
                'notRequired'       => true,
                'notExportable'     => true,
                'noList'            => true
            ),
        ),
        /* les parametres */
        'settings'                  => array(
            /* les indexes */
            'indexes'               => array(
            ),
            /* les relations */
            'relationships'         => array(
            ),

            'checkTuple'            => 'name',
            'orderList'             => 'name',
            'orderListDirection'    => 'ASC'
        ),
    );
