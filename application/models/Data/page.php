<?php
    return array(
        /* Les champs */
        'fields'                    => array(
            'displaymode'           => array(
                'type'              => 'data',
                'entity'            => 'displaymode',
                'fields'            => array('name'),
                'sort'              => 'name',
                'sortOrder'         => 'DESC',
                'label'             => 'Statut d\'affichage',
                'contentList'       => array('getValueEntity', 'displaymode', 'name'),
            ),
            'name'                  => array(
                'label'             => 'Nom',
            ),
            'url'                   => array(
                'label'             => 'URL',
                'noList'            => true
            ),
            'parent'                => array(
                'type'              => 'data',
                'entity'            => 'page',
                'fields'            => array('name'),
                'sort'              => 'name',
                'label'             => 'Page parent',
                'contentList'       => array('getValueEntity', 'page', 'name'),
                'canBeNull'         => true,
                'notRequired'       => true,
            ),
            'title'                 => array(
                'label'             => 'Titre',
                'noList'            => true,
                'isTranslated'      => true,
            ),
            'date_in'               => array(
                'label'             => 'Date de publication',
                'type'              => 'date',
                'noList'            => true,
                'canBeNull'         => true,
                'notRequired'       => true,
            ),
            'date_out'              => array(
                'label'             => 'Date de dépublication',
                'type'              => 'date',
                'noList'            => true,
                'canBeNull'         => true,
                'notRequired'       => true,
            ),
            'keywords'              => array(
                'label'             => 'Mots clés',
                'type'              => 'textarea',
                'canBeNull'         => true,
                'notRequired'       => true,
                'isTranslated'      => true,
                'noList'            => true
            ),
            'description'           => array(
                'label'             => 'Description',
                'type'              => 'textarea',
                'isTranslated'      => true,
                'canBeNull'         => true,
                'notRequired'       => true,
                'noList'            => true
            ),
            'header'                => array(
                'type'              => 'data',
                'entity'            => 'header',
                'fields'            => array('name'),
                'sort'              => 'name',
                'sortOrder'         => 'ASC',
                'label'             => 'Header',
                'contentList'       => array('getValueEntity', 'header', 'name'),
                'isTranslated'      => true,
                'canBeNull'         => true,
                'notRequired'       => true,
                'notExportable'     => true,
                'noList'            => true
            ),
            'footer'                => array(
                'type'              => 'data',
                'entity'            => 'footer',
                'fields'            => array('name'),
                'sort'              => 'name',
                'sortOrder'         => 'ASC',
                'label'             => 'Footer',
                'contentList'       => array('getValueEntity', 'footer', 'name'),
                'isTranslated'      => true,
                'canBeNull'         => true,
                'notRequired'       => true,
                'notExportable'     => true,
                'noList'            => true
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
                'displaymode'       => array(
                    'type'          => 'multiple'
                ),
                'header'            => array(
                    'type'          => 'multiple'
                ),
                'footer'            => array(
                    'type'          => 'multiple'
                ),
            ),
            /* les relations */
            'relationships'         => array(
                'displaymode'       => array(
                    'type'          => 'manyToOne',
                    'onDelete'      => 'cascade'
                ),
                'tagpages'          => array(
                    'type'          => 'manyToMany',
                    'onDelete'      => 'cascade'
                ),
                'header'            => array(
                    'type'          => 'manyToOne',
                    'onDelete'      => 'cascade'
                ),
                'footer'            => array(
                    'type'          => 'manyToOne',
                    'onDelete'      => 'cascade'
                ),
            ),
            'versioning'            => true,
            'checkTuple'            => 'url',
            'orderList'             => 'name',
            'orderListDirection'    => 'ASC'
        ),
    );
