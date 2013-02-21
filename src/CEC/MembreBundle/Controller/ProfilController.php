<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfilController extends Controller
{
    public function afficherAction($id)
    {
        // On récupère l'utilisateur
        if ($id == null) {
            $membre = $this->get('security.context')->getToken()->getUser();
        } else {
            $membreRepo = $this->getDoctrine()->getRepository('CECMembreBundle:Membre');
            $membre = $membreRepo->findOneById($id);
        }
        
        // Erreur si on a pas de membre
        if (!$membre) {
            throw new Exception('Utilisateur inconnu');    // Changer
        }
        
        return $this->render('CECMembreBundle:Profil:afficher.html.twig', array(
            'membre'    => $membre,
        ));
    }
}
