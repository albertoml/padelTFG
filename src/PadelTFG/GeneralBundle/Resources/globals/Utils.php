<?php

namespace PadelTFG\GeneralBundle\Resources\globals;

use Symfony\Component\HttpFoundation\Response as Response;
use PadelTFG\GeneralBundle\Resources\globals\Literals as Literals;

class Utils
{
	public function setResponse($status, $content){
        $response = new Response();
        $response->setStatusCode($status);
        $content = json_encode(array('key' => $content));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'origin, x-requested-with, content-type');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        $response->setContent($content);
        return $response;
    }

    public function setJsonResponse($status, $content){
        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'origin, x-requested-with, content-type');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        $response->setStatusCode($status);
        return $response;
    }

    public static function getUserGenders()
    {
        return array(Literals::GenderMale, Literals::GenderFemale);
    }

    public static function getGenders()
    {
        return array(Literals::GenderMale, Literals::GenderFemale, Literals::GenderMixed);
    }
}
