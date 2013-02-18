<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReglagesController extends Controller
{
    public function indexAction()
    {
        // Redirige vers les réglages du profil
        return $this->redirect($this->generateUrl('reglages_profil'));
    }
    
    public function profilAction(Request $request)
    {
        $membre = $this->get('security.context')->getToken()->getUser();
        
        $form = $this->createFormBuilder($membre)
            ->add('prenom')
            ->add('nom')
            ->add('email')
            ->add('telephone')
            ->getForm();
        
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);
            
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()
                    ->flush();
                $this->redirect($this->generateUrl('reglages_profil'));
            }
        }
        
        return $this->render('CECMembreBundle:Reglages:profil.html.twig', array(
            'form' => $form->createView(),
        ));
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
