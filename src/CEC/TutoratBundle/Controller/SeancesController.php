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
     *
     * @param integer $groupe: id du groupe de tutorat permettant de filtrer les séances.
     *                         Si null, affiche toutes les séances de tutorat.
     */
    public function tousAction()
    {
        // TODO
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
            $formView->getChild('debut')->setAttribute('placeholder', $groupe->getDebut()->format('h:m'));
            $formView->getChild('fin')->setAttribute('placeholder', $groupe->getFin()->format('h:m'));
        }
        
        return $this->render('CECTutoratBundle:Seances:voir.html.twig', array(
            'seance'       => $seance,
            'lyceens'      => $lyceens,
            'tuteurs'      => $tuteurs,
            'form'         => $formView,
            'afficher_modal' => $afficherModal,
        ));
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
