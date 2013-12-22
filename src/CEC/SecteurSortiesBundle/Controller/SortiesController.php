<?php

namespace CEC\SecteurSortiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurSortiesBundle\Entity\Sortie;
use CEC\SecteurSortiesBundle\Form\Type\SortieType;

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
     * @Route("/sortiesPassees", name="anciennes_sorties")
     * @Template()
     */
    public function voirAnciennesAction()
    {
        return array();
    }

    /**
     * Permet d'éditer une sortie
     *
     * @Route("/sortie/editer", name="editer_sortie")
     * @Template()
     *
     */
    public function editerAction()
    {
        $sortie = new Sortie();
        $form = $this->createForm(new SortieType(), $sortie);
        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->get('session')->setFlash('success', "La sortie a bien été ajoutée.");
                return $this->redirect($this->generateUrl('sorties'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * Supprime une sortie
     *
     */
    public function supprimerSortieAction($id)
    {
        return array();
    }
}
