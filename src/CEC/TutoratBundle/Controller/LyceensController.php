<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Lyceen;
use CEC\TutoratBundle\Form\Type\LyceenType;

class LyceensController extends Controller
{
    /**
     * Affiche la liste des lycéens
     */
    public function tousAction() {
        $lyceens = $this->getDoctrine()->getRepository('CECTutoratBundle:Lyceen')->findAll();    // tous les Lyceens
        usort($lyceens, function ($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        return $this->render('CECTutoratBundle:Lyceens:tous.html.twig', array('lyceens' => $lyceens));
    }
    
    /**
     * Affiche un lycée avec toutes ses informations
     *
     * @param integer $lyceen: id du lycéen
     */
    public function voirAction($lyceen) {
        $lyceen = $this->getDoctrine()->getRepository('CECTutoratBundle:Lyceen')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        
        return $this->render('CECTutoratBundle:Lyceens:voir.html.twig', array('lyceen' => $lyceen));
        
    }
    
    /**
     * Permet d'éditer un lycéen et d'enregistrer les modifications apportées
     *
     * @param integer $lyceen: id du lycéen
     */
    public function editerAction($lyceen) {
        $lyceen = $this->getDoctrine()->getRepository('CECTutoratBundle:Lyceen')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        
        $form = $this->createForm(new LyceenType(), $lyceen);
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Les informations du lycéen ont bien été mis à jour.');
                return $this->redirect($this->generateUrl('lyceen', array('lyceen' => $lyceen->getId())));
            }
        }
        
        return $this->render('CECTutoratBundle:Lyceens:editer.html.twig', array(
            'lyceen'        => $lyceen,
            'form'          => $form->createView(),
        ));
    }
    
    /**
     * Permet de créer un nouveau lycéen
     */
    public function creerAction() {
        $lyceen = new Lyceen();
        $form = $this->createForm(new LyceenType(), $lyceen);
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($lyceen);
                $em->flush();
                $this->get('session')->setFlash('success', 'Le lycéen a bien été créé.');
                return $this->redirect($this->generateUrl('lyceen', array('lyceen' => $lyceen->getId())));
            }
        }
        
        return $this->render('CECTutoratBundle:Lyceens:creer.html.twig', array('form' => $form->createView()));
    }
}
