<?php

namespace Bukatov\ApiTokenBundle\Controller;

use Bukatov\ApiTokenBundle\Security\Core\User\ApiUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenController extends Controller
{
    public function getApiTokenAction()
    {
        //$token = $this->getUser() instanceof ApiUserInterface ? (string)$this->getUser()->getApiToken() : '';

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