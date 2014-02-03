<?php
    namespace Thin;
    use Symfony\Component\HttpFoundation\ThinRequest as ThinRequest;

    class Bootstrap
    {
        private static $app;

        public static function init()
        {
            session_start();

            Request::$foundation = ThinRequest::createFromGlobals();

            define('NL', "\n");
            define('ISAJAX', Inflector::lower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');

            Utils::cleanCache();
            $logger = new Log();
            $app    = new Application;

            $app->setLogger($logger);
            Utils::set('isTranslate', false);

            Utils::set('app', $app);

            static::$app = $app;

            static::loadConfigs();
            static::loadDatas();
            static::routes();
            static::acl();
            static::dispatch();
            static::test();
            static::run();
        }

        private static function loadConfigs()
        {
            Config::load('application');
            Config::load('models', false);
            Config::load('routes', false);

            $iniData    = include(APPLICATION_PATH . DS . 'config' . DS . 'ini.php');

            $envIni     = APPLICATION_PATH . DS . 'config' . DS . Inflector::lower(APPLICATION_ENV) . '.php';
            if (File::exists($envIni)) {
                $iniData += include($envIni);
            }

            $ini        = new Iniconfig;

            $ini->populate($iniData);
            container()->setConfig($ini);
            container()->setServerDir(repl(DS . 'application', '', APPLICATION_PATH));
            $urlCheck = ake('REDIRECT_URL', $_SERVER) ? $_SERVER['REDIRECT_URL'] : $_SERVER['REQUEST_URI'];
            $tab = explode('/', $urlCheck);
            $firstWord = count($tab) > 2 ? $tab[2] : current($tab);
            $check = 'backadmin' == $firstWord || contain('cmsMedia-', $urlCheck);
            container()->setIsAdmin($check);
        }

        private static function loadDatas()
        {
            $dirData = STORAGE_PATH . DS . 'data';
            if (!is_dir(STORAGE_PATH)) {
                mkdir(STORAGE_PATH, 0777);
            }
            if (!is_dir($dirData)) {
                mkdir($dirData, 0777);
            }
            $datas = glob(APPLICATION_PATH . DS . 'models' . DS . 'Data' . DS . '*.php');
            $entities = array();
            if (count($datas)) {
                foreach ($datas as $model) {
                    $infos                      = include($model);
                    $tab                        = explode(DS, $model);
                    $entity                     = repl('.php', '', Inflector::lower(Arrays::last($tab)));
                    $entities[]                 = $entity;
                    $fields                     = $infos['fields'];
                    $settings                   = $infos['settings'];
                    Data::$_fields[$entity]     = $fields;
                    Data::$_settings[$entity]   = $settings;
                }
            }
            $dirDataTheme = APPLICATION_PATH . DS . 'models' . DS . 'Data' . DS . container()->getThemeName();
            if (is_dir($dirDataTheme)) {
                $datasTheme = glob($dirDataTheme . DS . '*.php');
                if (count($datasTheme)) {
                    foreach ($datasTheme as $model) {
                        $infos                      = include($model);
                        $tab                        = explode(DS, $model);
                        $entity                     = container()->getThemeName() . '_' .repl('.php', '', Inflector::lower(Arrays::last($tab)));
                        $entities[]                 = $entity;
                        $fields                     = $infos['fields'];
                        $settings                   = $infos['settings'];
                        Data::$_fields[$entity]     = $fields;
                        Data::$_settings[$entity]   = $settings;
                    }
                }
            }
            container()->setEntities($entities);
            $pages = Data::getAll('page');
            if(!count($pages)) {
                static::fixtures();
            }
        }

        private static function fixtures()
        {
            $adminTables        = Data::getAll('admintable');
            $adminUsers         = Data::getAll('adminuser');
            $adminactions       = Data::getAll('adminaction');
            $adminRights        = Data::getAll('adminright');
            $adminTaskStatus    = Data::getAll('admintaskstatus');
            $adminTaskType      = Data::getAll('admintasktype');
            $adminTaskTypes     = Data::getAll('admintasktype');
            $adminCountries     = Data::getAll('admincountry');
            $options            = Data::getAll('option');
            $pages              = Data::getAll('page');
            $displays           = Data::getAll('displaymode');
            $typeAssets         = Data::getAll('typeasset');
            $assets             = Data::getAll('asset');
            $headers            = Data::getAll('header');
            $footers            = Data::getAll('footer');

            if (!count($adminCountries)) {
                $list = fgc("http://web.gpweb.co/u/45880241/cdn/pays.csv");
                $rows = explode("\n", $list);
                foreach ($rows as $row) {
                    $row = repl('"', '', trim($row));
                    if (contain(';', $row)) {
                        list($id, $name, $upp, $low, $code) = explode(';', $row, 5);

                        $country = array(
                            'name' => $name,
                            'code' => $code
                        );
                        Data::add('admincountry', $country);
                        Data::getAll('admincountry');
                    }
                }
            }

            if (!count($adminTaskTypes)) {
                $types = array(
                    'Bogue',
                    'Snippet',
                    'SEO',
                    'Traduction',
                    'Graphisme',
                    'Contenu',
                    'Html',
                    'Css',
                    'Javascript',
                );
                foreach ($types as $type) {
                    $taskType = array(
                        'name' => $type
                    );
                    Data::add('admintasktype', $taskType);
                    Data::getAll('admintasktype');
                }
            }

            if (!count($adminTaskStatus)) {
                $allStatus = array(
                    1 => 'Attribuée',
                    4 => 'Terminée',
                    2 => 'En cours',
                    7 => 'En suspens',
                    6 => 'En attente d\'information',
                    3 => 'En test',
                    5 => 'Réattribuée',
                    8 => 'Annulée',
                );
                foreach ($allStatus as $priority => $status) {
                    $taskStatus = array(
                        'name' => $status,
                        'priority' => $priority
                    );
                    Data::add('admintaskstatus', $taskStatus);
                    Data::getAll('admintaskstatus');
                }
            }

            if (!count($adminTables)) {
                $entities = container()->getEntities();
                if (count($entities)) {
                    foreach ($entities as $entity) {
                        $table = array(
                            'name' => $entity
                        );
                        Data::add('admintable', $table);
                        Data::getAll('admintable');
                    }
                }
            }

            if (!count($adminactions)) {
                $actions = array(
                    'list',
                    'add',
                    'duplicate',
                    'view',
                    'delete',
                    'edit',
                    'import',
                    'export',
                    'search',
                    'empty_cache'
                );

                foreach ($actions as $action) {
                    $newAction = array(
                        'name' => $action
                    );
                    Data::add('adminaction', $newAction);
                    Data::getAll('adminaction');
                }
            }

            if (!count($adminUsers)) {
                $user = array(
                    'name'      => 'admin',
                    'firstname' => 'admin',
                    'login'     => 'admin',
                    'password'  => 'admin',
                    'email'     => 'admin@admin.com',
                );

                Data::add('adminuser', $user);
                Data::getAll('adminuser');
            }

            if (!count($adminRights)) {
                $sql        = new Querydata('adminuser');
                $res        = $sql->where('email = admin@admin.com')->get();
                $user       = $sql->first($res);

                $tables     = Data::getAll('admintable');
                $actions    = Data::getAll('adminaction');

                if (count($tables)) {
                    foreach ($tables as $table) {
                        $table = Data::getIt('admintable', $table);
                        foreach ($actions as $action) {
                            $action = Data::getIt('adminaction', $action);
                            $right = array(
                                'adminuser'     => $user->getId(),
                                'admintable'    => $table->getId(),
                                'adminaction'   => $action->getId()
                            );
                            Data::add('adminright', $right);
                            Data::getAll('adminright');
                        }
                    }
                }
            }

            if (!count($headers)) {
                $header = array(
                    'name' => 'main',
                    'html' => array('fr' => static::fix(fgc("http://web.gpweb.co/u/45880241/cdn/header.tpl")))
                );

                Data::add('header', $header);
                Data::getAll('header');
            }

            if (!count($footers)) {
                $footer = array(
                    'name' => 'main',
                    'html' => array('fr' => static::fix(fgc("http://web.gpweb.co/u/45880241/cdn/footer.tpl")))
                );

                Data::add('footer', $footer);
                Data::getAll('footer');
            }

            if (!count($typeAssets)) {
                $typeasset1 = array(
                    'name'  => 'css'
                );
                $typeasset2 = array(
                    'name'  => 'javascript'
                );

                Data::add('typeasset', $typeasset1);
                Data::getAll('typeasset');
                Data::add('typeasset', $typeasset2);
                Data::getAll('typeasset');
            }

            if (!count($assets)) {
                $sql = new Querydata('typeasset');
                $res = $sql->where('name = css')->get();
                $css = $sql->first($res);

                $sql = new Querydata('typeasset');
                $res = $sql->where('name = javascript')->get();
                $js  = $sql->first($res);

                $themeCss = array(
                    'typeasset'   => $css->getId(),
                    'name'        => 'theme',
                    'priority'    => 1,
                    'code'        => static::fix(fgc("http://web.gpweb.co/u/45880241/cdn/css.tpl"))
                );

                Data::add('asset', $themeCss);
                Data::getAll('asset');


                $themeJs = array(
                    'typeasset'   => $js->getId(),
                    'name'        => 'theme',
                    'priority'    => 1,
                    'code'        => 'function go(url) {document.location.href = url;}'
                );

                Data::add('asset', $themeJs);
                Data::getAll('asset');
            }

            if (!count($displays)) {
                $display1 = array(
                    'name'  => 'Online'
                );
                $display2 = array(
                    'name'  => 'Offline'
                );
                $display3 = array(
                    'name'  => 'Maintenance'
                );

                Data::add('displaymode', $display1);
                Data::getAll('displaymode');
                Data::add('displaymode', $display2);
                Data::getAll('displaymode');
                Data::add('displaymode', $display3);
                Data::getAll('displaymode');
            }

            if (!count($options)) {
                $option1 = array(
                    'name'  => 'default_language',
                    'value' => 'fr',
                );
                $option2 = array(
                    'name'  => 'page_languages',
                    'value' => 'fr',
                );
                $option3 = array(
                    'name'  => 'theme',
                    'value' => container()->getThemeName(),
                );
                $option4 = array(
                    'name'  => 'home_page_url',
                    'value' => 'home',
                );

                Data::add('option', $option1);
                Data::getAll('option');
                Data::add('option', $option2);
                Data::getAll('option');
                Data::add('option', $option3);
                Data::getAll('option');
                Data::add('option', $option4);
                Data::getAll('option');

                File::cpdir(THEME_PATH . DS . 'default', THEME_PATH . DS . container()->getThemeName());
                chmod(THEME_PATH . DS . container()->getThemeName() . DS . 'editor' . DS . 'source', 0777);
                chmod(THEME_PATH . DS . container()->getThemeName() . DS . 'editor' . DS . 'thumbs', 0777);
                chmod(THEME_PATH . DS . container()->getThemeName() . DS . 'assets', 0777);
                chmod(THEME_PATH . DS . container()->getThemeName() . DS . 'assets' . DS . 'css', 0777);
                chmod(THEME_PATH . DS . container()->getThemeName() . DS . 'assets' . DS . 'img', 0777);
                chmod(THEME_PATH . DS . container()->getThemeName() . DS . 'assets' . DS . 'js', 0777);
                chmod(THEME_PATH . DS . container()->getThemeName() . DS . 'assets' . DS . 'thumbs', 0777);
            }

            if (!count($pages)) {
                $sql = new Querydata('displaymode');
                $res = $sql->where('name = online')->get();
                $display = $sql->first($res);

                $sql = new Querydata('header');
                $res = $sql->where('name = main')->get();
                $header = $sql->first($res);

                $sql = new Querydata('footer');
                $res = $sql->where('name = main')->get();
                $footer = $sql->first($res);

                $home = array(
                    'displaymode'   => $display->getId(),
                    'header'        => $header->getId(),
                    'footer'        => $footer->getId(),
                    'name'          => 'Home',
                    'url'           => 'home',
                    'date_in'       => date('d-m-Y'),
                    'title'         => array(
                        'fr'        => 'Bienvenue'
                    ),
                    'html'          => array(
                        'fr'        => static::fix(fgc("http://web.gpweb.co/u/45880241/cdn/cms.tpl"))
                    ),
                    'parent'        => null,
                    'date_out'      => null,
                    'keywords'      => array(
                        'fr'        => null
                    ),
                    'description'   => array(
                        'fr'        => null
                    ),
                );

                Data::add('page', $home);
                Data::getAll('page');
            }
        }

        private static function fix($data)
        {
            return str_replace(
                array(
                    '##themeName##',
                    '##cms_url_theme()##'
                ),
                array(
                    container()->getThemeName(),
                    '/themes/' . container()->getThemeName()
                ),
                $data
            );
        }

        private static function acl()
        {
            $session    = session('admin');
            $dataRights = $session->getDataRights();
            if (null !== $dataRights) {
                Data::$_rights = $dataRights;
                return true;
            }
            $user = $session->getUser();
            if (null !== $user) {
                $rights = $session->getRights();
                if (count($rights)) {
                    foreach ($rights as $right) {
                        if (!ake($right->getAdmintable()->getName(), Data::$_rights)) {
                            Data::$_rights[$right->getAdmintable()->getName()] = array();
                        }
                        Data::$_rights[$right->getAdmintable()->getName()][$right->getAdminaction()->getName()] = true;
                    }
                }
                $session->setDataRights(Data::$_rights);
            }
            return false;
        }

        private static function routes()
        {
            // container()->addRoute(time());
            // container()->addRoute(rand(5, 1566698));
        }

        private static function dispatch()
        {
            if (true === container()->getIsAdmin()) {
                Router::dispatch();
                Router::language();
            } else {
                Cms::dispatch();
                Cms::language();
            }
        }

        private static function run()
        {
            if (true === container()->getIsAdmin()) {
                Router::run();
            } else {
                Cms::run();
            }
        }

        private static function test()
        {
            // $app = array(
            //     'name' => 'TV',
            //     'brand' => 'Philips',
            //     'price' => '499',
            //     'country' => 'France',
            // );
            // $sql = new Querydata('truc');
            // $all = $sql->where('name = TV')->get();
            // $config = array(
            //     'checkTuple'         => array('name', 'brand'),
            //     'orderList'          => 'name',
            //     'orderListDirection' => 'ASC'
            // );
            // container()->setConfigDataTruc($config);
            // dieDump($sql->first($all));
            // exit;
            // dieDump(cms_object('produits', 'tv')->getPrice());
            // $etat = Data::last('etat');
            // $produit = Data::first('produit');
            // $produit->setEtat($etat);
            // dieDump($produit->getEtat() == $etat);
            // $cool = function($a, $b) {
            //     return rand($a, $b);
            // };
            // container()->closure('cool', $cool);

            // $t =  container()->cool(20, 400);
            // dieDump($t);
            // $query  = new Querydata('youtube');
            // $query2 = new Querydata('youtube');
            // $res    = $query->where("title LIKE '%p%'")
            // ->whereAnd("title LIKE 'zzy'")
            // ->whereOr(
            //     $query2->where("title LIKE '%wwz%'")
            //     ->sub()
            // )
            // ->order('title')
            // ->get();
            // dieDump($res);
            // $test = Data::newApikey(array('key' => 'cool', 'resource' => 'OK'));
            // $test->save();
            // dieDump($test);
            // $videos = Youtube::getVideosByUser('TheKARAOKEChannel');
            // foreach ($videos as $video) {
            //     Data::add('youtube', $video, 'youtube_id');
            // }
            // dieDump($video);
            // $res = Data::query('youtube', '', 0, 0, 'title');
            // foreach ($res as $row) {
            //     echo '<a href="http://www.youtube.com/watch?v=' . $row->getYoutubeId() . '" target="_ytk">' . $row->getTitle() . '</a><hr />';
            // }
            // exit;
            // $mail = email()->setTo('gplusquellec@gmail.com')->setSubject('test')->setBody('test')->setHeaders("From: gg<gplusquellec@free.fr>");
            // dieDump($mail->send());
            // $smtp = container()->getIni();
            // dieDump($smtp->getSmtp());
            // $i = Allocine::save(10141);
            // dieDump($i);
            // $c = Data::getAll('film');
            // $obj = Data::getObject(current($c));
            // dieDump($obj->genre);
            // $test = mail('gplusquellec@gmail.com', 'test', 'test', "From:gp<hp@free.fr>");
            // $truc = new Truc;
            // $truc->app = 15;
            // $truc->boot = function () use ($truc) {
            //     $val = rand(1, 9999);
            //     // $truc->rand = $val;
            //     $truc['rand'] = $val;
            //     return $truc;
            // };
            // var_dump($test);exit;
            // info(static::$app);
            // $tab = array(
            //     'title' => 'Star Wars',
            //     'year' => '1978',
            //     'country' => 'United States',
            // );
            // $url = 'http://cc/api/eav/b4e290a5f1c3ac61704133a57021881b/set/movie/' . base64_encode(serialize($tab));
            // die($url);
            // $row = Data::add('apikey', array('resource' => 'eav', 'key' => 'b4e290a5f1c3ac61704133a57021881c'));
            // $cart = new Cart('Pilouf');
            // $cart->add('ok', 'tv', 1, 589);
            // $cart->add('456', 'pc', 29, 899);
            // $cart->remove('c1a4cde0b13dff4deb02f6a18c15b58c');m);
            // $session = Sessionbis::instance('test');
            // $session->setParam(time());
            // dieDump($row);
        }
    }
