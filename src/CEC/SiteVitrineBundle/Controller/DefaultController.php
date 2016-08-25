<?php

namespace CEC\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //En attendant que le site vitrine soit propre
        return $this->redirect($this->generateUrl('connexion'));
        //return $this->render('CECSiteVitrineBundle:Default:index.html.twig', array());
    }
}
