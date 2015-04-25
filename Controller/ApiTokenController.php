<?php

namespace Bukatov\ApiTokenBundle\Controller;

use Bukatov\ApiTokenBundle\Entity\ApiUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenController extends Controller
{
    public function getApiTokenAction(Request $request)
    {
        $apiToken = $this->getApiToken($this->getUser());

        return new Response($apiToken->getToken());
    }

    protected function getApiToken(ApiUserInterface $user)
    {
        $em = $this->get('doctrine')->getManager();
        $repository = $this->get('doctrine')->getRepository('BukatovApiTokenBundle:ApiToken');

        $apiToken = $repository->createOrUpdateApiToken($user);

        if (!$apiToken->getId()) {
            $em->persist($apiToken);
        }

        $em->flush();

        return $apiToken;
    }

    public function invalidateApiTokenAction(Request $request)
    {
        $this->invalidateApiToken($this->getUser());

        return new Response();
    }

    protected function invalidateApiToken(ApiUserInterface $user)
    {
        $em = $this->get('doctrine')->getManager();

        if ($apiToken = $user->getApiToken()) {
            $em->remove($apiToken);
            $em->flush();
        }
    }
}