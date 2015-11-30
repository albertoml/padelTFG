<?php

namespace PadelTFG\GeneralBundle\Resources\globals;

use Symfony\Component\HttpFoundation\Response as Response;

class Utils
{
	public function setResponse($status, $content){
        $response = new Response();
        $response->setStatusCode($status);
        $response->headers->set('Content-Type', 'application/text');
        $response->setContent($content);
        return $response;
    }

    public function setJsonResponse($status, $content){
        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($status);
        return $response;
    }
}
