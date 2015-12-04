<?php

namespace PadelTFG\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response as Response;

class WebController extends Controller
{
    public function indexAction()
    {
        //return $this->render('GeneralBundle:Default:index.html.twig'); 
        return new Response('<html><body>Hello !</body></html>');
        //return $this->redirectToRoute('/Applications/XAMPP/xamppfiles/htdocs/padelTFG/web/ui/index.html', array(), 301);
    }
}
