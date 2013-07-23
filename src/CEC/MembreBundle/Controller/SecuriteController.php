<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class SecuriteController extends Controller
{
    /**
     * Connexion au site interne.
     * Cette page est appelé automatiquement par le composant Security de Symfony.
     * Elle présente un formulaire permettant d'entrer un identifiant (prénom + nom) ainsi
     * qu'un mot de passe ; un bouton connexion lance la procédure d'authentification.
     *
     * @Template()
     */
    public function connexionAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        
        // Récupère les erreurs de connexion si nécessaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $erreur = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $erreur = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return array(
            // Dernier nom d'utilisateur entré
            'dernier_utilisateur'    => $session->get(SecurityContext::LAST_USERNAME),
            'erreur'                 => $erreur,
        );
    }
}
