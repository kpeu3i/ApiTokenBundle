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
        if ($apiToken = $this->getUser()->getApiToken()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($apiToken);
            $em->flush($apiToken);
        }

        return new Response();
    }
}