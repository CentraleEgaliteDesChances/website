<?php

namespace CEC\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function menuAction()
    {
        $membre = $this->getUser();
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver votre profil');
        
        
    }
}
