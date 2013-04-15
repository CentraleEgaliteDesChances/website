<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfilController extends Controller
{
    public function voirAction($membre)
    {
        // On récupère l'utilisateur
        if ($membre == null) {
            $membre = $this->get('security.context')->getToken()->getUser();
        } else {
            $membreRepo = $this->getDoctrine()->getRepository('CECMembreBundle:Membre');
            $membre = $membreRepo->findOneById($membre);
        }
        
        // Erreur si on a pas de membre
        if (!$membre) {
            throw new Exception('Utilisateur inconnu');    // Changer
        }
        
        return $this->render('CECMembreBundle:Profil:voir.html.twig', array(
            'membre'    => $membre,
        ));
    }
}
