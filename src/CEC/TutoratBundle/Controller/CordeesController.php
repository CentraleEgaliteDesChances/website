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
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')
            ->findAllForYear();
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'lycees' => $lycees,
        ));
    }
    
    /*
     * Affiche les lycées d'une cordée
     */
    public function voirAction($id)
    {
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')
            ->findAllForCordeeIdAndYear($id);
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'lycees' => $lycees,
        ));
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
