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
            container()->setIsAdmin(strstr($_SERVER['REQUEST_URI'], '/backadmin') ? true : false);
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
