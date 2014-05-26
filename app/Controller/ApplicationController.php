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
        return $app['twig']->render('twigs/main.html.twig', array(
          'twigs' => $app['sections'],
          'sectionsFolder' => $app['sectionsFolder'],
          'activeRoute' => $request->get("_route")
        ));
    }

}