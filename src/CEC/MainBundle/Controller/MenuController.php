<?php

namespace CEC\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function menuAction()
    {
        return $this->render('CECMainBundle:Menu:menu.html.twig');
    }
}