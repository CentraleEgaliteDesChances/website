<?php

namespace CEC\CalendrierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CalendrierController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CECCalendrierBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * Affiche le planning de toutes les séances de tutorat.
     */
    public function afficherAction()
    {
        return $this->render('CECCalendrierBundle::planning.html.twig');
    }
}
