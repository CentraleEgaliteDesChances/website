<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReglagesController extends Controller
{
    public function indexAction()
    {
        // Redirige vers les réglages du profil
        return $this->redirect($this->generateUrl('reglages_profil'));
    }
    
    public function profilAction()
    {
        return $this->render('CECMembreBundle:Reglages:profil.html.twig');
    }
    
    public function groupesDeTutoratAction()
    {
        return $this->render('CECMembreBundle:Reglages:profil.html.twig');
    }
    
    public function mesSecteursAction()
    {
        return $this->render('CECMembreBundle:Reglages:profil.html.twig');
    }
}
