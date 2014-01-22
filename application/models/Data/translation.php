<?php
    return array(
        /* Les champs */
        'fields'                => array(
            'page'              => array(
                'type'          => 'data',
                'entity'        => 'page',
                'fields'        => array('name'),
                'sort'          => 'name',
                'contentList'   => array('getValueEntity', 'page', 'name'),
            ),
            'key'               => array(
                'label'         => 'cle',
            ),
            'value'             => array(
                'label'         => 'Valeur',
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
            'indexes'           => array(
                'page'          => array(
                    'type'      => 'multiple'
                ),
            ),
            /* les relations */
            'relationships'     => array(
                'page'          => array(
                    'type'      => 'manyToOne',
                    'onDelete'  => 'cascade'
                ),
            ),
            'checkTuple'         => array('key', 'page'),
            'orderList'          => 'name',
            'orderListDirection' => 'ASC'
        ),
    );
