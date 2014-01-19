<?php
    return array(
        'fields'                    => array(
            'type'                  => array(
                'label'             => 'Type',
                'type'              => 'data',
                'entity'            => 'mediatype',
                'fields'            => array('name'),
                'sort'              => 'name',
                'contentList'       => array('getValueEntity', 'mediatype', 'name')
            ),
            'name'                  => array(
                'label'             => 'Nom'
            ),
            'file'                  => array(
                'type'              => 'file',
                'notSortable'       => true,
                'notSearchable'     => true,
                'contentList'       => array('displayMedia', null, null),
                'label'             => 'Fichier'
            ),
        ),
        'settings'                  => array(
            'orderList'             => 'name',
            'checkTuple'            => array('name', 'type'),
            'orderListDirection'    => 'ASC',
        ),
    );
