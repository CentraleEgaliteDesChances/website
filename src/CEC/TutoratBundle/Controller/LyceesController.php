<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Lycee;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\TutoratBundle\Form\Type\LyceeType;

class LyceesController extends Controller
{
    /**
     * Affiche la page d'un lycée.
     *
     * @param integer $lycee: id du lycée
     */
    public function voirAction($lycee)
    {
        $lycee = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->find($lycee);
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycée !');
            
        // On trouve les tuteurs, les lycéens et les séances à venir
        $tuteurs = array();
        $lyceens = array();
        $seances = array();
        foreach ($lycee->getGroupes() as $groupe) {
            $tuteurs = array_merge($tuteurs, $groupe->getTuteurs()->toArray());
            $lyceens = array_merge($lyceens, $groupe->getLyceens()->toArray());
            
            // On rassemble les séances à venir
            $groupeSeances = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->findComingByGroupe($groupe);
            $seances = array_merge($seances);
        }
        
        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        usort($lyceens, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        return $this->render('CECTutoratBundle:Lycees:voir.html.twig', array(
            'lycee'        => $lycee,
            'lyceens'      => $lyceens,
            'tuteurs'      => $tuteurs,
            'seances'      => $seances,
        ));
    }
    
    /**
     * Affiche un résumé des informations du lycée :
     * nom, adresse, cordée, statut, numéro de téléphone, source/pivot, ZEP ou non,
     * proviseur, référent, type de tutorat, niveaux concernés, nombre de lycéens et de tuteurs.
     *
     * @param Lycee $lycee: lycée
     */
    public function apercuAction(Lycee $lycee)
    {
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycée !');
        
        // On récupère le proviseur et le référent
        $enseignants = $lycee->getEnseignants();
        $proviseur = null;
        $referent = null;
        foreach ($enseignants as $enseignant) {
            $role = $enseignant->getRole();
            if (strstr($role, 'Professeur référent')) $referent = $enseignant;
            if (strstr($role, 'Chef d\'établissement')) $proviseur = $enseignant;
        }
            
        // On trouve les tuteurs, les lycéens, les niveaux, les types de tutorat et les prochaines séances
        $tuteurs = array();
        $lyceens = array();
        $types = array();
        $niveaux = array();
        $seances = array();
        foreach ($lycee->getGroupes() as $groupe) {
            $tuteurs = array_merge($tuteurs, $groupe->getTuteurs()->toArray());
            $lyceens = array_merge($lyceens, $groupe->getLyceens()->toArray());
            if (!in_array($groupe->getTypeDeTutorat(), $types)) $types[] = $groupe->getTypeDeTutorat();
            if (!in_array($groupe->getNiveau(), $niveaux)) $niveaux[] = $groupe->getNiveau();
        }
        
        return $this->render('CECTutoratBundle:Lycees:apercu.html.twig', array(
            'lycee'    => $lycee,
            'referent' => $referent,
            'proviseur'=> $proviseur,
            'tuteurs'  => $tuteurs,
            'lyceens'  => $lyceens,
            'niveaux'  => $niveaux,
            'types'    => $types,
        ));
    }
    
    /**
     * Permet l'édition d'un lycée.
     *
     * @param integer $lycee: id du lycée
     */
    public function editerAction($lycee)
    {
        $lycee = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->find($lycee);
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycée !');
            
        // On trouve les tuteurs, les lycéens et les séances à venir
        $tuteurs = array();
        $lyceens = array();
        $seances = array();
        foreach ($lycee->getGroupes() as $groupe) {
            $tuteurs = array_merge($tuteurs, $groupe->getTuteurs()->toArray());
            $lyceens = array_merge($lyceens, $groupe->getLyceens()->toArray());
            
            // On rassemble les séances à venir
            $groupeSeances = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->findComingByGroupe($groupe);
            $seances = array_merge($seances);
        }
        
        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        usort($lyceens, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        // On génère les formulaires
        $lyceeForm = $this->createForm(new LyceeType(), $lycee);
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST')
        {
            $lyceeForm->bindRequest($request);
            if ($lyceeForm->isValid())
            {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Les informations du lycée ont bien été enregistrées');
                return $this->redirect($this->generateUrl('lycee', array('lycee' => $lycee->getId())));
            }
        }
        
        return $this->render('CECTutoratBundle:Lycees:editer.html.twig', array(
            'lycee'        => $lycee,
            'lyceens'      => $lyceens,
            'tuteurs'      => $tuteurs,
            'seances'      => $seances,
            'lycee_form'   => $lyceeForm->createView(),
        ));
    } 
    
    
    /**
     * Supprime un groupe de tutorat du lycée
     *
     * @param integer $lycee: id du lycée
     * @param integer $groupe: id du groupe
     */
    public function supprimerGroupeAction($lycee, $groupe)
    {
        $lycee = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->find($lycee);
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycée !');
            
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
        
        // On supprime les références aux séances associées, et on supprime le groupe
        $entityManager = $this->getDoctrine()->getEntityManager();
        foreach ($groupe->getSeances() as $seance) $seance->setGroupe(null);
        foreach ($groupe->getLyceens() as $lyceen) $lyceen->setGroupe(null);
        foreach ($groupe->getTuteurs() as $tuteur) $tuteur->setGroupe(null);
        $entityManager->remove($groupe);
        
        $this->getDoctrine()->getEntityManager()->flush();
        $this->get('session')->setFlash('alert', 'Le groupe de tutorat a bien été supprimé. Les séances, lycéens et tuteurs associés ont donc été retirés de ce groupe de tutorat avant sa suppression ; les statistiques associées — présences, nombre d\'heures de tutorat, nombre de tuteurs, activités utilisées — ont par conséquent été conservées.');
        return $this->redirect($this->generateUrl('editer_lycee', array('lycee' => $lycee->getId())));
    }

}
