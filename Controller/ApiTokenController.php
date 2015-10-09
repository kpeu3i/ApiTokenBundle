<?php

namespace Bukatov\ApiTokenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenController extends Controller
{
    public function getApiTokenAction()
    {
        $token = (string)$this->container->get('security.token_storage')->getToken()->getApiToken();

        return new Response($token);
    }

    public function invalidateApiTokenAction()
    {
        $token = (string)$this->container->get('security.token_storage')->getToken()->getApiToken();

        $this->get('bukatov_api_token.manager')->delete($token);

        return new Response();
    }
}