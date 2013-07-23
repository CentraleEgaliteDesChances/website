<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MembresController extends Controller
{
    /**
     * Affiche la liste de tous les membres.
     * Cette page affiche simplement la liste de tous les membres enregistrés sur le site internet.
     *
     * @Route("/membres")
     * @Template()
     */
    public function tousAction()
    {
        $membres = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->findAll();    // tous les Membres
        return array('membres' => $membres);
    }

    /**
     * Affiche le profil d'un membre.
     *
     * @param integer $membre: id du membre, null pour afficher le profil du membre connecté
     *
     * @Route("/membres/{membre}", requirements = { "membre" = "\d+" })
     * @Template()
     */
    public function voirAction($membre)
    {
        $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->findOneById($membre);
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver le profil !');
        
        return array(
            'membre'    => $membre,
        );
    }
}
