<?php

namespace CEC\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CECSiteVitrineBundle:Default:index_simple.html.twig', array());
    }
}
