<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CEC\MembreBundle\Form\Type\InformationsGeneralesType;

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
        
        $nomInformationsGenerales = 'informations_generales';
        $infomationsGenerales = $this->get('form.factory')
            ->createNamedBuilder($nomInformationsGenerales, new InformationsGeneralesType(), $membre)
            ->getForm();
        
        if ($request->getMethod() == 'POST') {
            if ($request->request->has($nomInformationsGenerales))
            {
                $infomationsGenerales->bindRequest($request);
                if ($infomationsGenerales->isValid()) {
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->redirect($this->generateUrl('reglages_profil'));
                }
            }
        }
        
        return $this->render('CECMembreBundle:Reglages:profil.html.twig', array(
            'informations_generales' => $infomationsGenerales->createView(),
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
