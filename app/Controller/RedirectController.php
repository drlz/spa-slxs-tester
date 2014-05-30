<?php 
namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class RedirectController
{

    public function redirectAction(Request $request, Application $app, $path)
    {	
	    return $app->redirect($app['url_generator']->generate($path));
    }

}