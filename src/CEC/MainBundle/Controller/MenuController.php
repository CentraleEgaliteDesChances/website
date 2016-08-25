<?php

namespace CEC\MainBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

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
        $crARediger = 0;
        if ($groupe) {
            $crARediger = count($doctrine->getRepository('CECActiviteBundle:CompteRendu')->findARedigerByGroupe($groupe));

            foreach($groupe->getSeances() as $seance)
            {
                $anneeScolaire = AnneeScolaire::withDate();
                if($anneeScolaire->contientDate($seance->getDate()) and count($seance->getCompteRendus()) == 0)
                {
                    $crARediger++;
                }
            }
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

    public function menuParentAction()
    {
        $parent = $this->getUser();
        if (!$parent) throw $this->createNotFoundException('Impossible de trouver votre profil !');

        $projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();

        //On recupere la liste des lycees dans lequel sont les enfants du parent
        $eleves = $parent->getEleves();
        $lycees = new ArrayCollection();
        foreach ($eleves as $eleve) {
            if (!($lycees->contains($eleve->getLycee()))) {
                $lycees->add($eleve->getLycee());
            }
        }

        return $this->render('CECMainBundle:Menu:menu_parent.html.twig', array(
            'parent' => $parent,
            'projets' => $projets,
            'lycees' => $lycees
        ));
    }

}
