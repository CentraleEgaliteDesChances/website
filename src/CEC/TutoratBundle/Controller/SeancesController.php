<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Seance;
use CEC\TutoratBundle\Form\Type\SeanceType;

class SeancesController extends Controller
{
    /**
     * Affiche le planning des séances de tutorat,
     * pour un groupe de tutorat en particulier, ou pour tous les groupes.
     * Offre un lien pour ajouter rapidement une séance pour un groupe en particulier.
     *
     * @param integer $groupe: id du groupe de tutorat permettant de filtrer les séances.
     *                         Si null, affiche toutes les séances de tutorat.
     * @param boolean $ajoutSeance: si true, afficher le dialogue pour ajouter une séance.
     */
    public function toutesAction($groupe, $ajoutSeance)
    {
        // Par défaut, pas de formulaire et on a pas d'objet groupe
        $formView = null;
        $objetGroupe = null;
        
        // Par défaut, on masque le modal
        $afficherModal = false;
                
        if ($groupe)
        {
            // Récupère le groupe associé
            $objetGroupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
            if (!$objetGroupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
        
            // Génère le formulaire de création de séance
            $nouvelleSeance = new Seance();
            $nouvelleSeance->setGroupe($objetGroupe);
            $form = $this->createForm(new SeanceType(), $nouvelleSeance);
            
            // Par défaut, on affiche le modal si l'ajout de séance est demandée
            $afficherModal = $ajoutSeance;
            
            $request = $this->getRequest();
            if ($request->getMethod() == 'POST')
            {
                $form->bindRequest($request);
                if ($form->isValid())
                {
                    $entityManager = $this->getDoctrine()->getEntityManager();
                    $entityManager->persist($nouvelleSeance);
                    $entityManager->flush();
                    $this->get('session')->setFlash('success', 'La séance de tutorat a bien été ajoutée.');
                    return $this->redirect($this->generateUrl('toutes_seances', array('groupe' => $groupe)));
                } else {
                    $afficherModal = true;
                }
            }
            
            // On change les placeholders du formulaire de création de séance
            // pour correspondre aux infos du groupe de tutorat.
            $formView = $form->createView();
            $formView->getChild('lieu')->setAttribute('placeholder', $objetGroupe->getLieu());
            $formView->getChild('rendezVous')->setAttribute('placeholder', $objetGroupe->getRendezVous());
            $formView->getChild('debut')->setAttribute('placeholder', $objetGroupe->getDebut()->format('H:i'));
            $formView->getChild('fin')->setAttribute('placeholder', $objetGroupe->getFin()->format('H:i'));
        }
    
        // Restreint si nécessaire l'affichage des séances pour un groupe
        $this->get('cec_tutorat.seances_planning_event_listener')->setGroupe($groupe);
        
        return $this->render('CECTutoratBundle:Seances:planning.html.twig', array(
            'groupe'         => $objetGroupe,
            'form'           => $formView,
            'afficher_modal' => $afficherModal,
        ));
    }

    /**
     * Affiche la page de la séance de tutorat.
     * Il s'agit aussi de la page qui s'affiche sur le tableau de bord lorsqu'une
     * prochaine séance est disponible.
     *
     * @param integer $seance: id de la séance de tutorat
     */
    public function voirAction($seance)
    {
        $seance = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->find($seance);
        if (!$seance) throw $this->createNotFoundException('Impossible de trouver la séance de tutorat !');
        
        // On détermine si la séance est à venir ou non
        $seanceAVenir = $seance->getGroupe()
            and $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->findOneAVenir($seance->getGroupe()) == $seance;
        
        // Rassemble les lycéens et les tuteurs du groupe de tutorat
        if ($seance->getGroupe())
        {
            $lyceens = !is_null($seance->getGroupe()->getLyceens()) ? $seance->getGroupe()->getLyceens()->toArray() : array();
            $tuteurs = !is_null($seance->getGroupe()->getTuteurs()) ? $seance->getGroupe()->getTuteurs()->toArray() : array();
        } else {
            $lyceens = array();
            $tuteurs = array();
        }
                
        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function($a, $b) { return strcmp($a->getNom(), $b->getNom()); });
        usort($lyceens, function($a, $b) { return strcmp($a->getNom(), $b->getNom()); });
        
        // On génère le formulaire d'édition de la séance
        $form = $this->createForm(new SeanceType(), $seance);
        
        // Par défaut, on masque le modal
        $afficherModal = false;
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Les informations de la séance ont bien été modifiées.');
                return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
            } else {
                $afficherModal = true;
            }
        }
        
        // On change les placeholders pour correspondre aux infos du groupe de tutorat
        $formView = $form->createView();
        if ($seance->getGroupe())
        {
            $groupe = $seance->getGroupe();
            $formView->getChild('lieu')->setAttribute('placeholder', $groupe->getLieu());
            $formView->getChild('rendezVous')->setAttribute('placeholder', $groupe->getRendezVous());
            $formView->getChild('debut')->setAttribute('placeholder', $groupe->getDebut()->format('H:i'));
            $formView->getChild('fin')->setAttribute('placeholder', $groupe->getFin()->format('H:i'));
        }
        
        return $this->render('CECTutoratBundle:Seances:voir.html.twig', array(
            'seance'         => $seance,
            'lyceens'        => $lyceens,
            'tuteurs'        => $tuteurs,
            'form'           => $formView,
            'afficher_modal' => $afficherModal,
            'seance_a_venir' => $seanceAVenir,
        ));
    }
    
    /**
     * Supprime la séance de tutorat.
     *
     * @param integer $seance: id de la séance de tutorat
     */
    public function supprimerAction($seance)
    {
        $seance = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->find($seance);
        if (!$seance) throw $this->createNotFoundException('Impossible de trouver la séance de tutorat !');
        
        // Retire le groupe en le gardant en mémoire
        $groupe = $seance->getGroupe();
        $seance->setGroupe(null);
        
        // Retire les tuteurs et les lycéens
        foreach ($seance->getTuteurs() as $tuteur) $seance->removeTuteur($tuteur);
        foreach ($seance->getLyceens() as $lyceen) $seance->removeLyceen($lyceen);
        
        // Supprime la séance
        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->remove($seance);
        $entityManager->flush();
        
        $this->get('session')->setFlash('success', 'La séance de tutorat a bien été supprimée.');
        return $this->redirect($this->generateUrl('groupe', array('groupe' => $groupe->getId())));
    }
    
    /**
     * Bascule l'état d'un tuteur pour cette séance :
     * s'il est marqué comme participant, on le retire et vice-versa.
     *
     * @param integer $seance: id de la séance de tutorat
     * @param integer $tuteur: id du tuteur dont l'état doit être basculé
     */
    public function basculerTuteurAction($seance, $tuteur)
    {
        $seance = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->find($seance);
        if (!$seance) throw $this->createNotFoundException('Impossible de trouver la séance de tutorat !');
        
        $tuteur = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($tuteur);
        if (!$tuteur) throw $this->createNotFoundException('Impossible de trouver le tuteur !');
        
        // On bascule l'état
        if ($seance->getTuteurs()->contains($tuteur))
        {
            $seance->removeTuteur($tuteur);
        } else {
            $seance->addTuteur($tuteur);
        }
        
        $this->getDoctrine()->getEntityManager()->flush();
        return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
    }
    
}
