<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfilController extends Controller
{
    /**
     * Affiche la liste des membres
     */
    public function tousAction()
    {
        $membres = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->findAll();    // tous les Membres
        return $this->render('CECMembreBundle:Profil:tous.html.twig', array('membres' => $membres));
    }

    /**
     * Affiche le profil d'un membre
     *
     * @param integer $membre: id du membre dont on veux afficher le profil,
         null pour afficher le profil du membre connecté
     */
    public function voirAction($membre)
    {
        // On récupère l'utilisateur
        if ($membre == null)
        {
            $membre = $this->getUser();
        } else {
            $membreRepo = $this->getDoctrine()->getRepository('CECMembreBundle:Membre');
            $membre = $membreRepo->findOneById($membre);
        }
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver le profil demandé !');
        
        return $this->render('CECMembreBundle:Profil:voir.html.twig', array(
            'membre'    => $membre,
        ));
    }
}
