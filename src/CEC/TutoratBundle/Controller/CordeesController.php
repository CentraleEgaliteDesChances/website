<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Form\Type\NomCordeeType;
use CEC\TutoratBundle\Entity\Cordee;
use CEC\TutoratBundle\Entity\Lycee;

class CordeesController extends Controller
{
    /**
     * Helper
     * Retourne les lycées sources d'une liste de lycée
     *
     * @param array(Lycee) $lycees: lycées
     * @return array(Lycee)
     */
    public function filterLyceesSources($lycees)
    {
        $sources = array_filter($lycees, function(Lycee $lycee) {
            return !$lycee->getPivot();
        });
        return $sources;
    }
    
    /**
     * Helper
     * Retourne les lycées pivots d'une liste de lycée
     *
     * @param array(Lycee) $lycees: lycées
     * @return array(Lycee)
     */
    public function filterLyceesPivots($lycees)
    {
        $pivots = array_filter($lycees, function(Lycee $lycee) {
            return $lycee->getPivot();
        });
        return $pivots;
    }

    /**
     * Affiche tous les lycées actifs
     */
    public function toutesAction()
    {
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->findAll();    // Tous les lycées
        $lyceesActifs = array_filter($lycees, function (Lycee $lycee) {
            return $lycee->getCordee();
        });
        
        // Formulaire de création d'une cordée
        $nouvelleCordee = new Cordee();
        $form = $this->createForm(new NomCordeeType(), $nouvelleCordee);
        
        if ($this->getRequest()->getMethod() == 'POST') {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($nouvelleCordee);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add(
                    'success', 
                    'La cordée a bien été créée, mais elle ne contient encore aucun lycée. Vous pouvez maintenant ajouter à la cordée des lycées sources et pivots pour l\'année en cours.'
                );
                
                return $this->redirect($this->generateUrl('editer_cordee', array(
                    'cordee' => $nouvelleCordee->getId()
                )));
            }
        }
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'sources' => $this->filterLyceesSources($lyceesActifs),
            'pivots'  => $this->filterLyceesPivots($lyceesActifs),
            'form'    => $form->createView(),
            'afficher_modal' => $this->getRequest()->getMethod() == 'POST',
        ));
    }
    
    /**
     * Affiche les lycées d'une cordée
     *
     * @param integer $cordee: id de la cordée a afficher
     */
    public function voirAction($cordee)
    {
        $cordee = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee')->find($cordee);
        if (!$cordee) throw $this->createNotFoundException('Impossible de trouver la cordée !');
        
        $lycees = $cordee->getLycees();
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'cordee'  => $cordee,
            'sources' => $this->filterLyceesSources($lycees),
            'pivots'  => $this->filterLyceesPivots($lycees),
        ));
    }
    
    /**
     * Permet l'édition d'une cordée de la réussite
     *
     * @param integer $cordee: id de la cordée
     */
    public function editerAction($cordee)
    {
        // On récupère la cordée
        $cordee = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee')->find($cordee);
        if (!$cordee) throw $this->createNotFoundException('Impossible de trouver la cordée !');
            
        // On récupère les lycées de la cordée et les lycées sans cordée
        $lyceesCordee = $cordee->getLycees();    // Lycées de la cordée
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->findAll();    // Tous les lycées
        $lyceesInactifs = array_filter($lycees, function (Lycee $lycee) {
            return !$lycee->getCordee();
        });
        
        // On trie les lycées à afficher par ordre alphabétique
        $lycees = array_merge($lyceesCordee, $lyceesInactifs);
        usort($lycees, function($a, $b) {

            return strcmp($a->getNom(), $b->getNom());
        });
    
        // Formulaire de changement de nom de la cordée
        $nomNomForm = 'nom_form';
        $nomForm = $this->get('form.factory')
            ->createNamedBuilder($nomNomForm, new NomCordeeType(), $cordee)
            ->getForm();
            
        if ($this->getRequest()->isMethod('POST'))
        {
            $nomForm->handleRequest($this->getRequest());
            if ($nomForm->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->getFlashBag()->add('success', 'Le nom de la cordée a bien été mise à jour.');
                return $this->redirect($this->generateUrl('editer_cordee', array('cordee' => $cordee->getId())));
            }
        }
        
            
        return $this->render('CECTutoratBundle:Cordees:editer.html.twig', array(
            'cordee'        => $cordee,
            'nom_form'      => $nomForm->createView(),
            'sources'       => $this->filterLyceesSources($lycees),
            'pivots'        => $this->filterLyceesPivots($lycees),
            'lycees_cordee' => $lyceesCordee,
        ));
    }
    
    /**
     * Bascule l'état d'un lycée pour une cordée
     *
     * @param integer $cordee: id de la cordée
     * @param integer $lycee: id du lycée
     */
    public function basculerLyceeAction($cordee, $lycee)
    {
        // On récupère la cordée et le lycée
        $doctrine = $this->getDoctrine();
        $cordee = $doctrine->getRepository('CECTutoratBundle:Cordee')->find($cordee);
        $lycee = $doctrine->getRepository('CECTutoratBundle:Lycee')->find($lycee);
        
        if (!$cordee) throw $this->createNotFoundException('Impossible de trouver la cordée !');
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycée !');
        
        // On ajoute fait basculer l'état du lycée dans la cordée
        if ($lycee->getCordee() === $cordee) {
            $lycee->setCordee(null);
        } else {
            $lycee->setCordee($cordee);
        }
        
        $doctrine->getEntityManager()->flush();
        return $this->redirect($this->generateUrl('editer_cordee', array('cordee' => $cordee->getId() )));
    }
    
    /**
     * Désactive une cordee :
     * Retire tous les lycées de la cordée, qui devient donc vide.
     */
    public function desactiverAction($cordee)
    {
        // Récupère la cordée et ses lycées
        $cordee = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee')->find($cordee);
        if (!$cordee) throw $this->createNotFoundException('Impossible de trouver la cordée !');
        
        foreach ($cordee->getLycees() as $lycee) $lycee->setCordee(null);
        $this->getDoctrine()->getEntityManager()->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'La cordée a bien été désactivée.');
        return $this->redirect($this->generateUrl('toutes_cordees'));
    }
    
    /**
     * Affiche tous les lycées inactifs
     */
    public function anciennesAction()
    {
        // On cherche tous les lycées actifs
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->findAll();    // Tous les lycées
        $lyceesInactifs = array_filter($lycees, function (Lycee $lycee) {
            return !$lycee->getCordee();
        });
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'sources' => $this->filterLyceesSources($lyceesInactifs),
            'pivots'  => $this->filterLyceesPivots($lyceesInactifs),
        ));
    }
    
    /**
     * Affiche le menu présentant toutes les cordées
     *
     * @param Request $request: requête originale
     */
    public function menuAction($request)
    {
        $repo = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee');
        $cordees = $repo->findAll();
        
        $cordeesActives = array_filter($cordees, function (Cordee $cordee) { return $cordee->isActive(); });
        $cordeesMortes = array_filter($cordees, function (Cordee $cordee) { return !$cordee->isActive(); });
        
        return $this->render('CECTutoratBundle:Cordees:menu.html.twig', array(
            'cordees_actives' => $cordeesActives,
            'cordees_mortes'  => $cordeesMortes,
            'request'         => $request,
        ));
    }
}
