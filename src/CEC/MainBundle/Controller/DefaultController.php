<?php

namespace CEC\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CECMainBundle:Default:index.html.twig', array('name' => $name));
    }
}
