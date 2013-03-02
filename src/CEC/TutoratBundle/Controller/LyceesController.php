<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\MainBundle\Classes\AnneeScolaire;
use CEC\TutoratBundle\Entity\Lycee;

class LyceesController extends Controller
{
    /**
     * Helper
     * Retourne la liste des cordées actuelles d'un lycée
     *
     * @param Lycee $lycee: lycée
     * @return array(Cordee)
     */
    public function getCordeesForLycee(Lycee $lycee)
    {
        // On considère tous les changements de partenariat pour ce lycée
        $changements = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:ChangementCordeeLycee')
            ->findByLycee($lycee);
        
        // On effectue les modifications
        $cordees = array();
        foreach ($changements as $changement) {
            if ($changement->getAction() == ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT) {
                $cordees = array_merge($cordees, array($changement->getCordee()));
            } elseif ($changement->getAction() == ChangementCordeeLycee::CHANGEMENT_ACTION_SUPPRESSION) {
                $cordees = array_diff($cordees, array($changement->getCordee()));
            }
        }
        
        return $cordees;
    }
    
    /**
     * Affiche la page d'un lycée.
     *
     * @param integer $lycee: id du lycée
     */
    public function voirAction($lycee)
    {
        $lycee = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->find($lycee);
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycée !');
        
        return $this->render('CECTutoratBundle:Lycees:voir.html.twig', array(
            'lycee'    => $lycee,
        ));
    }
    
    /**
     * Affiche un résumé des informations du lycée :
     * nom, adresse, cordée, statut, numéro de téléphone, source/pivot, ZEP ou non,
     * proviseur, référent, type de tutorat, niveaux concernés, nombre de lycéens et de tuteurs.
     *
     * @param Lycee $lycee: lycée
     */
    public function apercuAction(Lycee $lycee)
    {
        return $this->render('CECTutoratBundle:Lycees:apercu.html.twig', array(
            'lycee'    => $lycee,
        ));
    }
}
