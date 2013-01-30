<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CECMembreBundle:Default:index.html.twig');
    }
}
