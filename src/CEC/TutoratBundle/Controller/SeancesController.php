<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Seance;

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
     * @param integer $seance: id de la seance de tutorat
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
        
        return $this->render('CECTutoratBundle:Seances:voir.html.twig', array(
            'seance'       => $seance,
            'lyceens'      => $lyceens,
            'tuteurs'      => $tuteurs,
        ));
    }
}
