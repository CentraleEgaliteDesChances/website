<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Cordee;
use CEC\TutoratBundle\Entity\Lycee;
use CEC\TutoratBundle\Entity\CordeeLyceeReference;

class CordeesController extends Controller
{
    /*
     * Affiche tous les lycées
     */
    public function toutesAction()
    {
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig');
    }
    
    /*
     * Affiche les lycées d'une cordée
     */
    public function voirAction($id)
    {
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig');
    }
    
    /*
     * Affiche le menu présentant toutes les cordées
     */
    public function menuAction($route)
    {
        $cordees = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee')
            ->findAll();
        return $this->render('CECTutoratBundle:Cordees:menu.html.twig', array(
            'cordees' => $cordees,
            'route'   => $route,
        ));
    }
}
