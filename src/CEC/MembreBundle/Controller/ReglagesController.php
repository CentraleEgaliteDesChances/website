<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use CEC\MembreBundle\Form\Type\InformationsGeneralesType;
use CEC\MembreBundle\Form\Type\MotDePasseType;

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
            
        $nomMotDePasse = 'mot_de_passe';
        $motDePasse = $this->get('form.factory')
            ->createNamedBuilder($nomMotDePasse, new MotDePasseType())
            ->getForm();
        
        if ($request->getMethod() == 'POST') {
            if ($request->request->has($nomInformationsGenerales))
            {
                $infomationsGenerales->bindRequest($request);
                if ($infomationsGenerales->isValid()) {
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->get('session')->setFlash('success', 'Les modifications ont bien été enregistrées.');
                    $this->redirect($this->generateUrl('reglages_profil'));
                }
            }
            
            if ($request->request->has($nomMotDePasse))
            {
                $motDePasse->bindRequest($request);
                if ($motDePasse->isValid()) {
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($membre);
                    $password = $encoder->encodePassword($motDePasse->getData()['motDePasse'], $membre->getSalt());
                    $membre->setMotDePasse($password);
                    
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->get('session')->setFlash('success', 'Le mot de passe a bien été modifié.');
                    $this->redirect($this->generateUrl('reglages_profil'));
                }
            }
        }
        
        return $this->render('CECMembreBundle:Reglages:profil.html.twig', array(
            'informations_generales' => $infomationsGenerales->createView(),
            'mot_de_passe'           => $motDePasse->createView(),
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
