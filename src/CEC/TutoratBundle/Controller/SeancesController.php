<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Seance;
use CEC\TutoratBundle\Form\Type\SeanceType;
use CEC\ActiviteBundle\Form\Type\CompteRenduType;

class SeancesController extends Controller
{
    /**
     * Affiche le planning de toutes les séances de tutorat.
     */
    public function toutesAction()
    {
        return $this->render('CECTutoratBundle:Seances:planning.html.twig');
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
        
        // On génère les formulaires de compte-rendu
        $crForms = array();
        foreach ($seance->getCompteRendus() as $compteRendu) {
            $compteRendu->setAuteur($this->getUser());
            $crForms[$compteRendu->getId()] = $this->createForm(new CompteRenduType(), $compteRendu);
        }
                
        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function($a, $b) { return strcmp($a->getNom(), $b->getNom()); });
        usort($lyceens, function($a, $b) { return strcmp($a->getNom(), $b->getNom()); });
        
        // On génère le formulaire d'édition de la séance
        $form = $this->createForm(new SeanceType(), $seance);
        
        // Par défaut, on masque le modal
        $afficherModal = false;
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST' and $request->request->has('editer_seance'))
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
        if ($request->getMethod() == 'POST' and $request->request->has('editer_cr'))
        {
            $compteRenduId = $request->request->get('cr_id');
            $compteRendu = $this->getDoctrine()->getRepository('CECActiviteBundle:CompteRendu')->findOneBy(array(
                'id' => $compteRenduId,
                'seance' => $seance->getId(),
            ));
            if (!$compteRendu) throw $this->createNotFoundException('Impossible de trouver le compte-rendu a éditer !');
            
            $crForm = $crForms[$compteRenduId];
            $crForm->bindRequest($request);
            if ($crForm->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Le compte-rendu de séance portant sur l\'activité "' . $compteRendu->getActivite()->getTitre() . '" a bien été envoyé.');
                return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
            } else {
                $this->get('session')->setFlash('error', 'Une erreur s\'est glissée dans le compte-rendu ; merci de vous y reporter pour plus d\'informations.');
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
        
        // On génère les vues de formulaires pour les CR
        $crFormViews = array();
        foreach ($crForms as $compteRenduId => $form) $crFormViews[$compteRenduId] = $form->createView();
        
        // On charge les informations de séance à afficher
        $infos = array();
        
        return $this->render('CECTutoratBundle:Seances:voir.html.twig', array(
            'seance'         => $seance,
            'lyceens'        => $lyceens,
            'tuteurs'        => $tuteurs,
            'form'           => $formView,
            'afficher_modal' => $afficherModal,
            'seance_a_venir' => $seanceAVenir,
            'cr_forms'       => $crFormViews,
            'infos'          => $infos,
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
