<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CEC\TutoratBundle\Form\Type\NomCordeeType;
use CEC\MainBundle\Classes\AnneeScolaire;
use CEC\TutoratBundle\Entity\Cordee;
use CEC\TutoratBundle\Entity\Lycee;
use CEC\TutoratBundle\Entity\ChangementCordeeLycee;

class CordeesController extends Controller
{
    /**
     * Helper
     * Retourne la liste des cordées actuelles d'un lycée
     *
     * @param Lycee $lycee: lycée
     * @return array(Cordee)
     */
    public function getCordeesForLycee(Lycee $lycee)
    {
        // On considère tous les changements de partenariat pour ce lycée
        $changements = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:ChangementCordeeLycee')
            ->findByLycee($lycee);
        
        // On effectue les modifications
        $cordees = array();
        foreach ($changements as $changement) {
            if ($changement->getAction() == ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT) {
                $cordees = array_merge($cordees, array($changement->getCordee()));
            } elseif ($changement->getAction() == ChangementCordeeLycee::CHANGEMENT_ACTION_SUPPRESSION) {
                $cordees = array_diff($cordees, array($changement->getCordee()));
            }
        }
        
        return $cordees;
    }
    
    /**
     * Helper
     * Retourne la liste des lycées actuels pour une cordée
     *
     * @param Cordee $cordee: cordée
     * @return array(Lycee)
     */
    public function getLyceesForCordee(Cordee $cordee)
    {
        // On considère tous les changements de partenariat pour cette cordée
        $changements = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:ChangementCordeeLycee')
            ->findByCordee($cordee);
        
        // On effectue les modifications
        $lycees = array();
        foreach ($changements as $changement) {
            if ($changement->getAction() == ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT) {
                $lycees = array_merge($lycees, array($changement->getLycee()));
            } elseif ($changement->getAction() == ChangementCordeeLycee::CHANGEMENT_ACTION_SUPPRESSION) {
                $lycees = array_diff($lycees, array($changement->getLycee()));
            }
        }
        
        return $lycees;
    }
    
    /**
     * Helper
     * Retourne les listes sources d'une liste de lycée
     *
     * @param array(Lycee) $lycees: lycées
     * @return array(Lycee)
     */
    public function filterLyceesSources($lycee)
    {
        $sources = array_filter($lycee, function($lycee) {
            return !$lycee->getPivot();
        });
        return $sources;
    }
    
    /**
     * Helper
     * Retourne les listes pivots d'une liste de lycée
     *
     * @param array(Lycee) $lycees: lycées
     * @return array(Lycee)
     */
    public function filterLyceesPivots($lycee)
    {
        $pivots = array_filter($lycee, function($lycee) {
            return $lycee->getPivot();
        });
        return $pivots;
    }
    
    /**
     * Helper
     * Effectue un changement de partenariat entre une cordée et un lycée.
     * Si le lycée était partenaire, il ne l'est plus et vice-versa.
     *
     * @param Cordee $cordee: cordée
     * @param Lycee $lycee: lycée
     */
    public function changerPartenariat(Cordee $cordee, Lycee $lycee)
    {
        $changement = new ChangementCordeeLycee();
        $anneeScolaire = new AnneeScolaire();
        $changement->setAnnee($anneeScolaire->getAnneeScolaire())
            ->setLycee($lycee)
            ->setCordee($cordee)
            ->setAction( in_array($lycee, $this->getLyceesForCordee($cordee)) ? ChangementCordeeLycee::CHANGEMENT_ACTION_SUPPRESSION : ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT );
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($changement);
        $em->flush();
    }    

    /**
     * Affiche tous les lycées actifs
     */
    public function toutesAction()
    {
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->findAll();    // Tous les lycées
        $lyceesActifs = array();
        $cordees = array();
        foreach ($lycees as $lycee)
        {
            if ( count($this->getCordeesForLycee($lycee)) > 0 )
            {
                $lyceesActifs[] = $lycee;
                $cordees[$lycee->getId()] = $this->getCordeesForLycee($lycee);
            }
        }
        
        // Formulaire de création d'une cordée
        $nouvelleCordee = new Cordee();
        $form = $this->createForm(new NomCordeeType(), $nouvelleCordee);
        
        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($nouvelleCordee);
                $em->flush();
                
                $this->get('session')->setFlash(
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
            'cordees' => $cordees,
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
        $cordee = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee')
            ->find($cordee);
        $lycees = $this->getLyceesForCordee($cordee);
    
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
        $cordee = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee')
            ->find($cordee);
            
        // On récupère les lycées de la cordée et les lycées sans cordée
        $lyceesCordee = $this->getLyceesForCordee($cordee);    // Lycées de la cordée
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->findAll();    // Tous les lycées
        $lyceesInactifs = array();
        foreach ($lycees as $lycee)
        {
            if ( count($this->getCordeesForLycee($lycee)) == 0 ) $lyceesInactifs[] = $lycee;
        }
        
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
            
        if ($this->getRequest()->getMethod() == 'POST')
        {
            $nomForm->bindRequest($this->getRequest());
            if (false and $nomForm->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Le nom de la cordée a bien été mise à jour.');
                return $this->redirect($this->generateUrl('editer_cordee', array('cordee' => $cordee)));
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
    public function changerLyceeAction($cordee, $lycee)
    {
        // On récupère la cordée et le lycée
        $doctrine = $this->getDoctrine();
        $cordee = $doctrine->getRepository('CECTutoratBundle:Cordee')->find($cordee);
        $lycee = $doctrine->getRepository('CECTutoratBundle:Lycee')->find($lycee);
        
        // On ajoute une changement de partenariat
        $this->changerPartenariat($cordee, $lycee);
                        
        $this->get('session')->setFlash('success', 'Les modifications ont été enregistrés.');
        return $this->redirect($this->generateUrl('editer_cordee', array('cordee' => $cordee->getId() )));
    }
    
    /**
     * Désactive une cordee
     */
    public function desactiverAction($cordee)
    {
        // Récupère la cordée et ses lycées
        $cordee = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee')->find($cordee);
        $lycees = $this->getLyceesForCordee($cordee);
        
        // Ajoute un changement de partenariat pour chacun des lycées
        foreach ($lycees as $lycee) {
            $this->changerPartenariat($cordee, $lycee);
        }
        
        $this->get('session')->setFlash('success', 'La cordée a bien été désactivée.');
        return $this->redirect($this->generateUrl('toutes_cordees'));
    }
    
    /**
     * Affiche tous les lycées inactifs
     */
    public function anciennesAction()
    {
        // On cherche tous les lycées actifs
        $lycees = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->findAll();    // Tous les lycées
        $lyceesActifs = array();
        foreach ($lycees as $lycee)
        {
            if ( count($this->getCordeesForLycee($lycee)) > 0 ) $lyceesActifs[] = $lycee;
        }
        
        // Par différence, on trouve les lycées inactifs
        $lyceesInactifs = array_diff($lycees, $lyceesActifs);
    
        return $this->render('CECTutoratBundle:Cordees:voir.html.twig', array(
            'sources' => $this->filterLyceesSources($lyceesInactifs),
            'pivots'  => $this->filterLyceesPivots($lyceesInactifs),
        ));
    }
    
    /**
     * Affiche le menu présentant toutes les cordées
     *
     * @param Request $request: requête original
     */
    public function menuAction($request)
    {
        $repo = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee');
        $cordees = $repo->findAll();
        $cordeesActives = array();
        foreach ($cordees as $cordee)
        {
            if ( count($this->getLyceesForCordee($cordee)) > 0 ) $cordeesActives[] = $cordee;
        }
        
        return $this->render('CECTutoratBundle:Cordees:menu.html.twig', array(
            'cordees_actives' => $cordeesActives,
            'cordees_mortes'  => array_diff($cordees, $cordeesActives),
            'request'         => $request,
        ));
    }
}
