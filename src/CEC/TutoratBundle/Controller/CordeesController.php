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
        $sources = array_filter($lycees, function($lycee) {
            return !$lycee->isPivot();
        });
        $pivots = array_filter($lycees, function($lycee) {
            return $lycee->isPivot();
        });
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'sources' => $sources,
            'pivots'  => $pivots,
        ));
    }
    
    /*
     * Affiche les lycées d'une cordée
     */
    public function voirAction($id)
    {
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')
            ->findAllForCordeeIdAndYear($id);
        $sources = array_filter($lycees, function($lycee) {
            return !$lycee->isPivot();
        });
        $pivots = array_filter($lycees, function($lycee) {
            return $lycee->isPivot();
        });
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'sources' => $sources,
            'pivots'  => $pivots,
        ));
    }
    
    /*
     * Permet l'édition d'une cordée de la réussite
     */
    public function editerAction($id)
    {
        return $this->render('CECTutoratBundle:Cordees:editer.html.twig', array(
            
        ));
    }
    
    /*
     * Affiche le menu présentant toutes les cordées
     *
     * @param Request $request: requête original
     */
    public function menuAction($request)
    {
        $repo = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee');
        $cordees = $repo->findAll();
        $cordeesActives = $repo->findAllActivesForYear();
        
        return $this->render('CECTutoratBundle:Cordees:menu.html.twig', array(
            'cordees_actives' => $cordeesActives,
            'cordees_mortes'  => array_diff($cordees, $cordeesActives),
            'request'         => $request,
        ));
    }
}
