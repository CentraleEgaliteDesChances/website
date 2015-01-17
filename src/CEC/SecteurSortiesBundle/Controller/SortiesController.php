<?php

namespace CEC\SecteurSortiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurSortiesBundle\Entity\Sortie;
use CEC\SecteurSortiesBundle\Form\Type\SortieType;
use CEC\SecteurSortiesBundle\Form\Type\CRSortieType;
use CEC\SecteurSortiesBundle\Form\Type\SansCRSortieType;

class SortiesController extends Controller
{
    /**
     * Affiche les sorties à venir
     *
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
     * Permet d'éditer une sortie ou de rédiger le CR
     *
     * @param integer $id Id de la sortie à modifier.
     * @param string $action Permet de différencier édition de la sortie et rédaction du CR
     * @Template()
     */
    public function editerAction($action, $id)
    {
        $sortie = $this->getDoctrine()->getRepository('CECSecteurSortiesBundle:Sortie')->find($id);
        if (!$sortie) throw $this->createNotFoundException('Impossible de trouver la sortie !');

        switch($action):
            case 'editer':
                $form = $this->createForm(new SansCRSortieType(), $sortie);
                break;
            case 'cr':
                $form = $this->createForm(new CRSortieType(), $sortie);
                break;
            case 'editeraveccr':
                $form = $this->createForm(new SortieType(), $sortie);
                break;
            default:
                $this->redirect($this->generateUrl('sorties'));
        endswitch;

        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->bindRequest($request);
            if ($form->isValid())
            {
                if ($action == 'cr')
                    $sortie->setOkCR(true);

                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($sortie);
                $entityManager->flush();

                switch($action):
                    case 'editer':
                        $this->get('session')->setFlash('success', "La sortie a bien été modifiée.");
                        return $this->redirect($this->generateUrl('sorties'));
                        break;
                    case 'cr':
                        $this->get('session')->setFlash('success', "Le CR de la sortie a bien été rédigé.");
                        return $this->redirect($this->generateUrl('anciennes_sorties'));
                        break;
                    case 'editeraveccr':
                        $this->get('session')->setFlash('success', "La sortie a bien été modifiée.");
                        return $this->redirect($this->generateUrl('anciennes_sorties'));
                        break;
                    default:
                        $this->redirect($this->generateUrl('sorties'));
                endswitch;
            }
        }

        if ($action == 'cr')
            $template = 'CECSecteurSortiesBundle:Sorties:editerCR.html.twig';
        else
            $template = 'CECSecteurSortiesBundle:Sorties:editer.html.twig';

        return $this->render($template, array(
            'form' => $form->createView(),
            'sortie' => $sortie
        ));
    }


    /**
     * Permet de créer une sortie
     *
     * @Template()
     */
    public function creerAction()
    {
        $sortie = new Sortie();
        $form = $this->createForm(new SansCRSortieType(), $sortie);
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
