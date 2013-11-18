<?php

namespace CEC\SecteurSortiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurSortiesBundle\Entity\Sortie;

class SortiesController extends Controller
{
    /**
     * Affiche les sorties à venir
     *
     * @Route("/sorties", name="sorties")
     * @Template()
     */
    public function voirAction()
    {
        $now = new \DateTime("now");

        $sorties = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->findFollowingSorties($now);

        return array(
            'sorties' => $sorties,
        );
    }

    /**
     * Affiche le menu des sorties
     *
     * @param Request $request: requête original
     */
    public function menuAction($request)
    {
        return $this->render('CECSecteurSortiesBundle:Sorties:menu.html.twig', array(
            'request'         => $request,
        ));
    }

    /**
     * Affiche les sorties passées
     *
     * @Route("/SortiesPassees", name="anciennes_sorties")
     * @Template()
     */
    public function voirAnciennesAction()
    {
        return array();
    }

    /**
     * Permet d'éditer une sortie
     *
     * @Route("/editer", name="editer_sortie")
     * @Template()
     */
    public function editerSortie()
    {
        return array();
    }

    /**
     * Supprime une sortie
     *
     */
    public function supprimerSortie($id)
    {
        return array();
    }
}
