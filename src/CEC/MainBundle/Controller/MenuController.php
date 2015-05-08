<?php

namespace CEC\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function menuAction()
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

        $projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
        
        return $this->render('CECMainBundle:Menu:menu.html.twig', array(
            'membre' => $membre,
            'seance_a_venir' => $seanceAVenir,
            'cr_a_rediger' => $crARediger,
            'projets' => $projets,
        ));
    }
	
	public function menuAnonAction()
	{
		return $this->render('CECMainBundle:Menu:menu_anon.html.twig', array());
	}
	
	public function menuProfAction()
	{
        $prof = $this->getUser();
        if (!$prof) throw $this->createNotFoundException('Impossible de trouver votre profil !');

        $projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();

        
		return $this->render('CECMainBundle:Menu:menu_prof.html.twig', array(
			'professeur' => $prof,
            'projets' => $projets,
		));
	}
	
	public function menuEleveAction()
	{
		$eleve = $this->getUser();
        if (!$eleve) throw $this->createNotFoundException('Impossible de trouver votre profil !');

        $projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();

        $seanceAVenir = false;
        if($groupe = $eleve->getGroupe())
        {
            $seanceAVenir = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->findOneAVenir($groupe);
        }
        
		return $this->render('CECMainBundle:Menu:menu_eleve.html.twig', array(
			'eleve' => $eleve,
            'seance_a_venir' => $seanceAVenir,
            'projets' => $projets,
		));
	}
}
