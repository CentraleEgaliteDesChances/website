<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Enseignant;

class EnseignantsController extends Controller
{
    /**
     * Affiche la liste des enseignants
     */
    public function tousAction() {
        $enseignants = $this->getDoctrine()->getRepository('CECTutoratBundle:Enseignant')->findAll();    // tous les Enseignants
        usort($enseignants, function ($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        return $this->render('CECTutoratBundle:Enseignants:tous.html.twig', array(
            'enseignants'        => $enseignants,
        ));
    }
    
    /**
     * Affiche un enseignant avec toutes ses informations
     *
     * @param integer $enseignant: id de l'enseignant
     */
    public function voirAction($enseignant) {
        $enseignant = $this->getDoctrine()->getRepository('CECTutoratBundle:Enseignant')->find($enseignant);
        if (!$enseignant) throw $this->createNotFoundException('Impossible de trouver l\'enseignant !');
        
        return $this->render('CECTutoratBundle:Enseignants:voir.html.twig', array(
            'enseignant'        => $enseignant,
        ));
        
    }
}
