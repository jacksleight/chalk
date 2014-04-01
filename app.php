<?php
use Coast\App, 
	Coast\App\Request, 
	Coast\App\Response, 
	Coast\Config,
	Coast\Path,
	Coast\Url;

/* Initialize */

chdir(__DIR__);
require 'vendor/autoload.php';

$config = new Config('config/config.php');
session_name('session');

$app = new \Ayre($config->envs);
$app->set('config', $config)
	->set('entity', new \Js\App\Entity())
	->set('html', new \Js\App\Html())
	->set('image', new \Js\App\Image())
//  ->set('message', new App\Message())
//  ->set('oembed', new App\Oembed($config->oembed))
	->set('view', new App\View([
		'dir' => 'views',
	]))
	->add('locale', new \Js\App\Locale([
		'cookie'  => 'locale',
		'locales' => [
			'en-GB' => 'en-GB@timezone=Europe/London;currency=GBP',
		],
	]))
	->add('router', new App\Router([
		'target' => new App\Controller(['namespace' => 'App\Controller']),
	]))
	->set('url', new App\Url($config->url + [
		'dir'     => 'public',
		'router'  => $app->router,
		'version' => function(Url $url, Path $path) {
			$url->path()->suffix(".{$path->modifyTime()->getTimestamp()}");
		},
	]))
	->notFoundHandler(function(Request $req, Response $res) {
		$res->status(404)
			->html($this->view->render('/error/not-found'));
	});
if (!$app->isDebug()) {
	$app->errorHandler(function(Request $req, Response $res, Exception $e) {
		$res->status(500)
			->html($this->view->render('/error/index', array('e' => $e)));
	});
}

/* Routes */

$app->router
	->all('index', '{controller}?/{action}?/{id}?', [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	]);

/* Memcached */

$memcached = new \Memcached();
$memcached->addServer($config->memcached['host'], $config->memcached['port']);
$app->set('memcached', $memcached);








/* Doctrine */

Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
    'vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
);

$cache = new \Doctrine\Common\Cache\ArrayCache;
// standard annotation reader
$annotationReader = new \Doctrine\Common\Annotations\AnnotationReader;
$cachedAnnotationReader = new \Doctrine\Common\Annotations\CachedReader(
    $annotationReader, // use reader
    $cache, // and a cache driver
    $app->isDebug()
);
// create a driver chain for metadata reading
$driverChain = new \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();
// load superclass metadata mapping only, into driver chain
// also registers Gedmo annotations.NOTE: you can personalize it
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
    $driverChain, // our metadata driver chain, to hook into
    $cachedAnnotationReader // our cached annotation reader
);

// now we want to register our application entities,
// for that we need another metadata driver used for Entity namespace
$annotationDriver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(
    $cachedAnnotationReader, // our cached annotation reader
    array('lib') // paths to look in
);
// NOTE: driver for application Entity can be different, Yaml, Xml or whatever
// register annotation driver for our application Entity namespace
$driverChain->addDriver($annotationDriver, 'Ayre');



$doct = new Doctrine\ORM\Configuration;
$doct->setProxyDir(sys_get_temp_dir());
$doct->setProxyNamespace('Proxy');
$doct->setAutoGenerateProxyClasses(false); // this can be based on production config.
// register metadata driver
$doct->setMetadataDriverImpl($driverChain);



$evm 	= new \Doctrine\Common\EventManager();


$sluggableListener = new Gedmo\Sluggable\SluggableListener;
// you should set the used annotation reader to listener, to avoid creating new one for mapping drivers
$sluggableListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($sluggableListener);



$timestampableListener = new Gedmo\Timestampable\TimestampableListener;
$timestampableListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($timestampableListener);

$uploadableListener = new Gedmo\Uploadable\UploadableListener;
$uploadableListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($uploadableListener);


$blameableListener = new \Gedmo\Blameable\BlameableListener();
$blameableListener->setAnnotationReader($cachedAnnotationReader);
$blameableListener->setUserValue('JACK'); // determine from your environment
$evm->addEventSubscriber($blameableListener);

// tree
$treeListener = new \Gedmo\Tree\TreeListener;
$treeListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($treeListener);


$em		= \Doctrine\ORM\EntityManager::create($config->database, $doct, $evm);
$app->set('em', $em);




$test = new EventTest();
$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $test);

class EventTest
{
    public function loadClassMetadata(\Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
       	$parts = explode('\\', $classMetadata->getName());
       	array_shift($parts);
       	$table = strtolower(implode('_', $parts));
        $classMetadata->setTableName($table);
    }
}





// $doct = new Doctrine\ORM\Configuration();
\Doctrine\DBAL\Types\Type::addType('json', '\Js\Doctrine\DBAL\Types\JSONType');
\Doctrine\DBAL\Types\Type::addType('url', '\Js\Doctrine\DBAL\Types\URLType');
// $doct->addCustomStringFunction('CEILING', '\JS\Doctrine\ORM\Query\Mysql\Ceiling');
// $doct->addCustomStringFunction('FIELD', '\JS\Doctrine\ORM\Query\Mysql\Field');
// $doct->addCustomStringFunction('FLOOR', '\JS\Doctrine\ORM\Query\Mysql\Floor');
// $doct->addCustomStringFunction('IF', '\JS\Doctrine\ORM\Query\Mysql\IfElse');
// $doct->addCustomStringFunction('ROUND', '\JS\Doctrine\ORM\Query\Mysql\Round');
// $doct->addCustomStringFunction('UTC_DATE', '\JS\Doctrine\ORM\Query\Mysql\UtcDate');
// $doct->addCustomStringFunction('UTC_TIME', '\JS\Doctrine\ORM\Query\Mysql\UtcTime');
// $doct->addCustomStringFunction('UTC_TIMESTAMP', '\JS\Doctrine\ORM\Query\Mysql\UtcTimestamp');
// $doct->setMetadataDriverImpl(new \Doctrine\ORM\Mapping\Driver\AnnotationDriver());
// $doct->setProxyDir('data/proxy');
// $doct->setProxyNamespace('Ayre\Proxy');
// $cache = new Doctrine\Common\Cache\MemcachedCache();
// $cache->setMemcached($memcached);
// $doct->setQueryCacheImpl($cache);
// $doct->setResultCacheImpl($cache);
// $doct->setMetadataCacheImpl($cache);
// $em = Doctrine\ORM\EntityManager::create(array_merge($config->database, [
// 	'charset' => 'utf8',
// ]), $doct);
// $em->getConnection()->exec("SET NAMES utf8");
// $app->set('em', $em);

/* Swift Mailer */

$swift = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
$app->set('swift', $swift);





return $app;