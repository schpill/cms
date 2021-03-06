<?php
    $acl = array();

    $user = new ACL;
    $user->setPassword('21232f297a57a5a743894a0e4a801fc3');
    $user->setEmail('user@domain.com');
    $user->setLogin('admin');
    $user->setFirstname('firstname');
    $user->setLastname('lastname');
    $user->setRights(
        array(
            'option'            => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'delete'        => true,
                'edit'          => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'block'             => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'delete'        => true,
                'edit'          => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'object'            => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'collection'        => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'page'              => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'translation'       => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'snippet'           => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'media'             => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'mediatype'         => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'displaymode'       => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'tag'               => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'tagpage'           => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'asset'             => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'typeasset'         => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'header'            => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
            'footer'            => array(
                'list'          => true,
                'add'           => true,
                'duplicate'     => true,
                'view'          => true,
                'edit'          => true,
                'delete'        => true,
                'import'        => true,
                'export'        => true,
                'search'        => true,
                'empty_cache'   => true,
            ),
        )
    );
    array_push($acl, $user);

    return $acl;
