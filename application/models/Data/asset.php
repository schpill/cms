<?php
    return array(
        /* Les champs */
        'fields'                => array(
            'typeasset'         => array(
                'type'          => 'data',
                'entity'        => 'typeasset',
                'fields'        => array('name'),
                'sort'          => 'name',
                'contentList'   => array('getValueEntity', 'typeasset', 'name'),
            ),
            'name'              => array(
                'label'         => 'Nom',
            ),
            'priority'          => array(
                'label'         => 'Priorité',
            ),
            'code'              => array(
                'label'         => 'Code',
                'type'          => 'code',
                'canBeNull'     => true,
                'notRequired'   => true,
                'notExportable' => true,
                'noList'        => true
            ),
        ),
        /* les parametres */
        'settings'              => array(
            /* les indexes */
            'indexes'           => array(
                'typeasset'     => array(
                    'type'      => 'multiple'
                ),
            ),
            /* les relations */
            'relationships'     => array(
                'typeasset'     => array(
                    'type'      => 'manyToOne',
                    'onDelete'  => 'cascade'
                ),
            ),
            'versioning'         => true,
            'checkTuple'         => array('name', 'typeasset'),
            'orderList'          => 'name',
            'orderListDirection' => 'ASC'
        ),
    );
