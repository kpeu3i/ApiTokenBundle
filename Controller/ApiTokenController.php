<?php

namespace Bukatov\ApiTokenBundle\Controller;

use Bukatov\ApiTokenBundle\Security\User\ApiTokenUserProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenController extends Controller
{
    public function getApiTokenAction()
    {
        $token = $this->getUser() instanceof ApiTokenUserProviderInterface ? (string)$this->getUser()->getApiToken() : '';

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