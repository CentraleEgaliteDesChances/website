<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CEC\TutoratBundle\Entity\Cordee;
use CEC\TutoratBundle\Entity\Lycee;
use CEC\TutoratBundle\Entity\CordeeLyceeReference;

class CordeesController extends Controller
{
    /*
     * Affiche tous les lycées actifs
     */
    public function toutesAction(Request $request)
    {
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')
            ->findAllForYear();
        $sources = array_filter($lycees, function($lycee) {
            return !$lycee->isPivot();
        });
        $pivots = array_filter($lycees, function($lycee) {
            return $lycee->isPivot();
        });
        
        // Formulaire de création d'une cordée
        $nouvelleCordee = new Cordee();
        $form = $this->createFormBuilder($nouvelleCordee)
            ->add('nom', null, array(
                'label_render' => false,
                'attr' => array('placeholder' => 'Nom de la cordée'),
            ))
            ->getForm();
        
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($nouvelleCordee);
                $em->flush();
                
                $this->get('session')->setFlash(
                    'success', 
                    'La cordée a bien été créée, mais elle ne contient encore aucun lycée. Ajouter à la cordée des lycées sources et pivots pour l\'année en cours.'
                );
                
                return $this->redirect($this->generateUrl('editer_cordee', array(
                    'id' => $nouvelleCordee->getId()
                )));
            }
        }
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'sources' => $sources,
            'pivots'  => $pivots,
            'form'    => $form->createView(),
            'afficher_modal' => $request->getMethod() == 'POST',
        ));
    }
    
    /*
     * Affiche les lycées d'une cordée
     */
    public function voirAction($id)
    {
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')
            ->findAllForCordeeIdAndYear($id);
        $sources = array_filter($lycees, function($lycee) {
            return !$lycee->isPivot();
        });
        $pivots = array_filter($lycees, function($lycee) {
            return $lycee->isPivot();
        });
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'sources' => $sources,
            'pivots'  => $pivots,
        ));
    }
    
    /*
     * Permet l'édition d'une cordée de la réussite
     */
    public function editerAction($id)
    {
        return $this->render('CECTutoratBundle:Cordees:editer.html.twig', array(
            
        ));
    }
    
    /*
     * Affiche tous les lycées inactifs
     */
    public function anciennesAction()
    {
        $repo = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee');
        $lyceesActifs = $repo->findAllForYear();
        $lycees = $repo->findAll();
        $lyceesInactifs = array_diff($lycees, $lyceesActifs);
        $sources = array_filter($lyceesInactifs, function($lycee) {
            return !$lycee->isPivot();
        });
        $pivots = array_filter($lyceesInactifs, function($lycee) {
            return $lycee->isPivot();
        });
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'sources' => $sources,
            'pivots'  => $pivots,
        ));
    }
    
    /*
     * Affiche le menu présentant toutes les cordées
     *
     * @param Request $request: requête original
     */
    public function menuAction($request)
    {
        $repo = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee');
        $cordees = $repo->findAll();
        $cordeesActives = $repo->findAllActivesForYear();
        
        return $this->render('CECTutoratBundle:Cordees:menu.html.twig', array(
            'cordees_actives' => $cordeesActives,
            'cordees_mortes'  => array_diff($cordees, $cordeesActives),
            'request'         => $request,
        ));
    }
}
