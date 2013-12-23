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
            'sorties' => $sorties
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
        $now = new \DateTime("now");

        $sorties = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->findPreviousSorties($now);

        return array(
            'sorties' => $sorties,
        );
    }

    /**
     * Permet d'éditer une sortie
     *
     * @param integer $id Id de la sortie à modifier.
     * @Route("/sorties/editer/{id}", name="editer_sortie")
     * @Template()
     */
    public function editerAction($id)
    {
        $sortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->find($id);
        if (!$sortie) throw $this->createNotFoundException('Impossible de trouver la sortie !');

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

                $this->get('session')->setFlash('success', "La sortie a bien été modifiée.");
                return $this->redirect($this->generateUrl('sorties'));
            }
        }

        return array(
            'form' => $form->createView(),
            'sortie' => $sortie
        );
    }


    /**
     * Permet de créer une sortie
     *
     * @Route("/sorties/creer", name="creer_sortie")
     * @Template()
     */
    public function creerAction()
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

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Supprime une sortie
     *
     * @param integer $id Id de la sortie à supprimer.
     * @Route("/sorties/supprimer/{id}", name="supprimer_sortie")
     * @Template()
     */
    public function supprimerSortieAction($id)
    {
        $sortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->find($id);
        if (!$sortie) throw $this->createNotFoundException('Impossible de trouver la sortie !');

        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->remove($sortie);
        $entityManager->flush();

        $this->get('session')->setFlash('success', 'La sortie a bien été définitivement supprimé.');
        return $this->redirect($this->generateUrl('sorties'));
    }
}
