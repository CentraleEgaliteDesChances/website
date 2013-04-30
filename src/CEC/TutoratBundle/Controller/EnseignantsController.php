<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Enseignant;
use CEC\TutoratBundle\Form\Type\EnseignantType;

class EnseignantsController extends Controller
{
    /**
     * Affiche la liste des enseignants
     */
    public function tousAction() {
        $enseignants = $this->getDoctrine()->getRepository('CECTutoratBundle:Enseignant')->findAll();    // tous les Enseignants
        usort($enseignants, function ($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        return $this->render('CECTutoratBundle:Enseignants:tous.html.twig', array('enseignants' => $enseignants));
    }
    
    /**
     * Affiche un enseignant avec toutes ses informations
     *
     * @param integer $enseignant: id de l'enseignant
     */
    public function voirAction($enseignant) {
        $enseignant = $this->getDoctrine()->getRepository('CECTutoratBundle:Enseignant')->find($enseignant);
        if (!$enseignant) throw $this->createNotFoundException('Impossible de trouver l\'enseignant !');
        
        return $this->render('CECTutoratBundle:Enseignants:voir.html.twig', array('enseignant' => $enseignant));
        
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
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($enseignant);
                $em->flush();
                $this->get('session')->setFlash('success', 'L\'enseignant a bien été créé.');
                return $this->redirect($this->generateUrl('enseignant', array('enseignant' => $enseignant->getId())));
            }
        }
        
        return $this->render('CECTutoratBundle:Enseignants:creer.html.twig', array('form' => $form->createView()));
    }
}
