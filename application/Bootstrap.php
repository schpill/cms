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
            if (count($datas)) {
                foreach ($datas as $model) {
                    $infos                      = include($model);
                    $tab                        = explode(DS, $model);
                    $entity                     = repl('.php', '', Inflector::lower(Arrays::last($tab)));
                    $fields                     = $infos['fields'];
                    $settings                   = $infos['settings'];
                    Data::$_fields[$entity]     = $fields;
                    Data::$_settings[$entity]   = $settings;
                }
            }
            static::fixtures();
        }

        private static function fixtures()
        {
            $options    = Data::getAll('option');
            $pages      = Data::getAll('page');
            $displays   = Data::getAll('displaymode');
            $typeAssets = Data::getAll('typeasset');
            $assets     = Data::getAll('asset');
            $headers    = Data::getAll('header');
            $footers    = Data::getAll('footer');

            if (!count($headers)) {
                $header = array(
                    'name' => 'main',
                    'html' => fgc("http://web.gpweb.co/u/45880241/cdn/header.tpl")
                );

                Data::add('header', $header);
                Data::getAll('asset');
            }

            if (!count($footers)) {
                $footer = array(
                    'name' => 'main',
                    'html' => fgc("http://web.gpweb.co/u/45880241/cdn/footer.tpl")
                );

                Data::add('footer', $footer);
                Data::getAll('asset');
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
                    'code'        => fgc("http://web.gpweb.co/u/45880241/cdn/css.tpl")
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

                Data::add('option', $option1);
                Data::getAll('option');
                Data::add('option', $option2);
                Data::getAll('option');
                Data::add('option', $option3);
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
                        'fr'        => fgc("http://web.gpweb.co/u/45880241/cdn/cms.tpl")
                    ),
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
