<?php

namespace CEC\CalendrierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CECCalendrierBundle:Default:index.html.twig', array('name' => $name));
    }
}
