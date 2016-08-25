<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TableauDeBordController extends Controller
{
    /**
     * Affiche le tableau de bord du membre.
     * Le tableau de bord est la page d'accueil du site interne.
     * Elle consiste en un logo et un message de bienvenue, ainsi qu'une liste
     * de liens contextuels utiles pour accéder rapidement aux fonctions principales.
     * @Template()
     */
    public function voirAction()
    {
        $membre = $this->getUser();
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver votre profil !');
        
        // On cherche si une séance est à venir
        $seanceAVenir = false;
        $doctrine = $this->getDoctrine();
        if ($groupe = $membre->getGroupe()) {
            $seanceAVenir = $doctrine->getRepository('CECTutoratBundle:Seance')->findOneAVenir($groupe);
        }
        
        // On cherche si des compte-rendus sont à rédiger
        $crARediger = array();
        if ($groupe) {
            $crARediger = $doctrine->getRepository('CECActiviteBundle:CompteRendu')->findARedigerByGroupe($groupe);
        }
        
        return array(
            'membre' => $membre,
            'seance_a_venir' => $seanceAVenir,
            'cr_a_rediger' => $crARediger,
        );
    }
	/**
     * Affiche le tableau de bord du professeurs.
     * Le tableau de bord est la page d'accueil du site interne.
     * Elle consiste en un logo et un message de bienvenue, ainsi qu'une liste
     * de liens contextuels utiles pour accéder rapidement aux fonctions principales.
     * @Template()
     */
	
	public function voirProfAction()
    {
        $prof = $this->getUser();
        if (!$prof) throw $this->createNotFoundException('Impossible de trouver votre profil !');

        
        return array(
            'professeur' => $prof,
        );
    }
	
	/**
     * Affiche le tableau de bord de l'élève.
     * Le tableau de bord est la page d'accueil du site interne.
     * Elle consiste en un logo et un message de bienvenue, ainsi qu'une liste
     * de liens contextuels utiles pour accéder rapidement aux fonctions principales.
     * @Template()
     */
	public function voirEleveAction()
    {
        $eleve = $this->getUser();
        if (!$eleve) throw $this->createNotFoundException('Impossible de trouver votre profil !');

        $seanceAVenir = false;
        $doctrine = $this->getDoctrine();
        if ($groupe = $eleve->getGroupe()) {
            $seanceAVenir = $doctrine->getRepository('CECTutoratBundle:Seance')->findOneAVenir($groupe);
        }

        
        return array(
            'eleve' => $eleve,
            'seance_a_venir' => $seanceAVenir
        );
    }
    /**
     * Affiche le tableau de bord d'un parent.
     * Le tableau de bord est la page d'accueil du site interne.
     * Elle consiste en un logo et un message de bienvenue, ainsi qu'une liste
     * de liens contextuels utiles pour accéder rapidement aux fonctions principales.
     * @Template()
     */
    public function voirParentAction()
    {
        $parent= $this->getUser();
        if (!$parent) throw $this->createNotFoundException('Impossible de trouver votre profil !');

        return array(
            'parent' => $parent,
        );
    }
}
