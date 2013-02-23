<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Cordee;
use CEC\TutoratBundle\Entity\Lycee;
use CEC\TutoratBundle\Entity\CordeeLyceeReference;

class CordeesController extends Controller
{
    public function voirAction($id)
    {
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig');
    }
    
    public function menuAction()
    {
        $cordees = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee')
            ->findAll();
        return $this->render('CECTutoratBundle:Cordees:menu.html.twig', array(
            'cordees' => $cordees,
        ));
    }
}
