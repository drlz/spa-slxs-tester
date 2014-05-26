<?php
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader as YamlRouting;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Nicl\Silex\MarkdownServiceProvider;

use Services\dataLoader;
use Services\sectionsLoader;
use Services\twigYearsToUrl;


$app = new Silex\Application();

$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/settings.yml'));

$app['debug'] = $app['config']['debug'];

  // cargador de templates
$app['sectionsFolder'] = '/twigs/sections';
$app['sectionsLoader'] = function () { return new sectionsLoader(); };
$app['sections'] = $app['sectionsLoader']->getSections($app, __DIR__ . $app['sectionsFolder']);

  // lector de datos JSON
$app['dataLoader'] = function () { return new dataLoader(); };

  /* Importando datos gracias a services -> dataloader.php
$seoFile = 'app/tdData.csv';
$app['dataLoader']->getData($seoFile, 'seo', $app, 'csv', 'url'); // -> nos genera $app['dataLoader.seo'] 
*/
  /* Otra importación
$seoFile = 'app/corresponsales.csv';
$app['dataLoader']->getData($seoFile, 'corresp', $app, 'csv', 'lugar'); // -> nos genera $app['dataLoader.corresp']
*/

//TWIG
$app->register(new Silex\Provider\TwigServiceProvider(), array( 'twig.path' => __DIR__.'/', ));

$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    /* sample Twig filter
    $twig->addExtension(new Services\twigYearsToUrl($app));*/
    return $twig; 
}));

$app->before(function (Request $request) use ($app) {
    $app['twig']->addGlobal('current_routeName', $request->get("_route"));
});

$app->register(new MarkdownServiceProvider());

$app['routes'] = $app->extend('routes', function (RouteCollection $routes, $app) {
    $loader     = new YamlRouting(new FileLocator(__DIR__ . '/'));
    $collection = $loader->load('routes.yml');
    $routes->addCollection($collection);

    return $routes;
});

$app->error(function (\Exception $e, $code) use($app) {
  if(!$app['debug']) {
    return new Response($app['twig']->render('twigs/error.html.twig'), $code);
  }
});

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->run();
