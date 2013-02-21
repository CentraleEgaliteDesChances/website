<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecuriteController extends Controller
{
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
        
        return $this->render('CECMembreBundle:Securite:connexion.html.twig', array(
            // Dernier nom d'utilisateur entré
            'dernier_utilisateur'    => $session->get(SecurityContext::LAST_USERNAME),
            'erreur'                 => $erreur,
        ));
    }
}
