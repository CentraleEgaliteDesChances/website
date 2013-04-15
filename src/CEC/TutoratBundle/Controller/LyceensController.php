<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Lyceen;
//use CEC\TutoratBundle\Form\Type\LyceenType;

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
        
        return $this->render('CECTutoratBundle:Lyceens:tous.html.twig', array('lyceens' => $lyceens,));
    }
    
    /**
     * Affiche un enseignant avec toutes ses informations
     *
     * @param integer $enseignant: id de l'enseignant
     */
    public function voirAction($enseignant) {
        $enseignant = $this->getDoctrine()->getRepository('CECTutoratBundle:Enseignant')->find($enseignant);
        if (!$enseignant) throw $this->createNotFoundException('Impossible de trouver l\'enseignant !');
        
        return $this->render('CECTutoratBundle:Enseignants:voir.html.twig', array(
            'enseignant'        => $enseignant,
        ));
        
    }
    
    /**
     * Permet d'éditer un enseignant et d'enregistrer les modifications apportées
     *
     * @param integer $enseignant: id de l'enseignant
     */
    public function editerAction($enseignant) {
        $enseignant = $this->getDoctrine()->getRepository('CECTutoratBundle:Enseignant')->find($enseignant);
        if (!$enseignant) throw $this->createNotFoundException('Impossible de trouver l\'enseignant !');
        
        $form = $this->createForm(new EnseignantType(), $enseignant);
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Les informations de l\'enseignant ont bien été mis à jour.');
                return $this->redirect($this->generateUrl('enseignant', array('enseignant' => $enseignant->getId())));
            }
        }
        
        return $this->render('CECTutoratBundle:Enseignants:editer.html.twig', array(
            'enseignant'        => $enseignant,
            'form'              => $form->createView(),
        ));
    }
    
    /**
     * Permet de créer un nouvel enseignant
     */
    public function creerAction() {
        $enseignant = new Enseignant();
        $form = $this->createForm(new EnseignantType(), $enseignant);
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'L\‘enseignant a bien été créé.');
                return $this->redirect($this->generateUrl('enseignant', array('enseignant' => $enseignant->getId())));
            }
        }
        
        return $this->render('CECTutoratBundle:Enseignants:creer.html.twig', array('form' => $form->createView()));
    }
}
