<?php

namespace Bukatov\ApiTokenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BukatovApiTokenBundle:Default:index.html.twig', array('name' => $name));
    }
}
