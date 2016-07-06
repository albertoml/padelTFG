<?php

namespace PadelTFG\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response as Response;

class WebController extends Controller
{
    public function indexAction()
    {
        return new Response('<html><body>Hello !</body></html>');
    }
}
