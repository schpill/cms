<?php
    return array(
        'fields' => array(
            'youtube_id'    => array('label'    => 'ID Y', 'noList' => true),
            'user'          => array('label'    => 'Auteur', 'noList'   => true),
            'title'         => array('label'    => 'Titre')
        ),
        'settings' => array(
            'singular'              => 'vidéo Youtube',
            'plural'                => 'vidéos Youtube',
            'orderList'             => 'title',
            'checkTuple'            => 'youtube_id',
            'orderListDirection'    => 'ASC'
        )
    );
