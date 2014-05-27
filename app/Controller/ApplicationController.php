<?php 
namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exceptions\ValidationException;

class ApplicationController
{

    public function indexAction(Request $request, Application $app)
    {	
        return $app['twig']->render($app['config']['silexUrls']['twigs'] . '/main.html.twig', array(
          'twigFolder' => $app['config']['silexUrls']['twigs'],
          'twigsArray' => $app['sections'],
          'sectionsFolder' => $app['sectionsFolder'],
          'activeRoute' => $request->get("_route"),
          'seo' => $app['dataLoader.seo'],
          'defaultRoute' => $app['config']['defaultRoute']
        ));
    }

}