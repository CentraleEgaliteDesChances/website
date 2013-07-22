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
        
        return $this->render('CECMainBundle:Menu:menu.html.twig', array(
            'membre' => $membre,
            'seance_a_venir' => $seanceAVenir,
            'cr_a_rediger' => $crARediger,
        ));
    }
}
