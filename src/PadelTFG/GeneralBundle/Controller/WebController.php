<?php

namespace PadelTFG\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class WebController extends Controller
{
    public function paginaAction($pagina)
    {
        return $this->render('GeneralBundle:Default:'.$pagina.'.html.twig'); 
    }
}
