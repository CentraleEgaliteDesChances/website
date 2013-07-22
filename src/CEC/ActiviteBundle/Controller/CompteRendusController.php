<?php

namespace CEC\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\TutoratBundle\Entity\Seance;
use CEC\ActiviteBundle\Entity\Activite;
use CEC\ActiviteBundle\Entity\CompteRendu;

class CompteRendusController extends Controller
{
    /**
     * Ajout d'une activité à une séance.
     * Cette méthode permet de créer un nouveau compte-rendu vierge pour une séance ne contenant
     * uniquement qu'une activité, sélectionnée par un tuteur. Cette étape est la première du
     * cycle de vie d'un compte-rendu de séance (voir classe CompteRendu pour plus d'infos).
     *
     * @param CEC\TutoratBundle\Entity\Seance $seance : id de la séance
     * @param CEC\ActiviteBundle\Entity\Activite $activite : id de l'activité à ajouter
     * @Route(
     *     "/seances/{seance}/ajout_activite/{activite}",
     *     requirements = { "seance" : "\d+", "activite" : "\d+" }
     * )
     */
    public function creerAction($seance, $activite)
    {
        $seance = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->find($seance);
        if (!$seance) throw $this->createNotFoundException('Impossible de trouver la séance !');
        
        $activite = $this->getDoctrine()->getRepository('CECActiviteBundle:Activite')->find($activite);
        if (!$activite) throw $this->createNotFoundException('Impossible de trouver l\’activité !');
        
        // On crée le compte-rendu s'il n'existe pas déjà
        $activites = $this->getDoctrine()->getRepository('CECActiviteBundle:Activite')->findBySeance($seance);
        if (!in_array($activite, $activites)) {
            $compteRendu = new CompteRendu();
            $compteRendu->setSeance($seance);
            $compteRendu->setActivite($activite);
        }
        
        // On persiste le compte-rendu
        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->persist($compteRendu);
        $entityManager->flush();
        
        return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
    }
    
    /**
     * Supprime un compte-rendu.
     * Un compte-rendu est supprimé lorsqu'une activité qui avait été choisie pour une séance est
     * par la suite déselectionnée.
     *
     * @param CEC\TutoratBundle\Entity\Seance $seance : id de la séance
     * @param CEC\ActiviteBundle\Entity\Activite $activite : id de l'activité à retirer
     * @Route(
     *     "/seances/{seance}/retrait_activite/{activite}",
     *     requirements = { "seance" : "\d+", "activite" : "\d+" }
     * )
     */
    public function supprimerAction($seance, $activite)
    {
        $seance = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->find($seance);
        if (!$seance) throw $this->createNotFoundException('Impossible de trouver la séance !');
        
        $activite = $this->getDoctrine()->getRepository('CECActiviteBundle:Activite')->find($activite);
        if (!$activite) throw $this->createNotFoundException('Impossible de trouver l\’activité !');
        
        // On retrouve le compte-rendu
        $compteRendu = $this->getDoctrine()->getRepository('CECActiviteBundle:CompteRendu')
            ->findOneBy(array('seance' => $seance->getId(), 'activite' => $activite->getId()));
        if (!$compteRendu) throw $this->createNotFoundException('Impossible de trouver l\’activité !');
        
        // On supprime le compte-rendu
        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->remove($compteRendu);
        $entityManager->flush();
        
        return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
    }
}
