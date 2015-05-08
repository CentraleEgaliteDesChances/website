<?php

namespace CEC\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\ActiviteBundle\Entity\QuizzActu;
use CEC\ActiviteBundle\Form\Type\QuizzActuType;

class QuizzActuController extends Controller
{
    /**
     * Affiche la liste des quizz actu
     *
     * @Template()
     */
    public function voirAction($id)
    {
        $quizzActus = $this->getDoctrine()->getRepository('CECActiviteBundle:QuizzActu')->findAll();
        if($id != 0){
            $quizzActu = $this->getDoctrine()->getRepository('CECActiviteBundle:QuizzActu')->find($id);
        }
        // si $id vaut 0, alors on affiche la page par défaut, càd avec le quizz actu de la semaine courante. S'il n'y en a pas, $quizzActu vaut null.
        else{
            $quizzActu = $this->getDoctrine()->getRepository('CECActiviteBundle:QuizzActu')->findCurrent();
        }

        return array(
            'quizzActus' => $quizzActus,
            'quizzActuVu' => $quizzActu,
        );
    }

    /**
     * Permet d'éditer un quizz actu
     *
     * @param integer $id Id du quizz actu à modifier.
     * @Template()
     */
    public function editerAction($id)
    {
        $quizzActu = $this->getDoctrine()->getRepository('CECActiviteBundle:QuizzActu')->find($id);
        if (!$quizzActu) throw $this->createNotFoundException('Impossible de trouver le quizz actu !');

        $form = $this->createForm(new QuizzActuType(), $quizzActu);

        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($quizzActu);
                $entityManager->flush();

                $this->get('session')->setFlash('success', "Le quizz actu a bien été modifiée.");
                return $this->redirect($this->generateUrl('quizzActu'));
            }
        }

        return $this->render('CECActiviteBundle:QuizzActu:editer.html.twig', array(
            'form' => $form->createView(),
            'quizzActu' => $quizzActu
        ));
    }


    /**
     * Permet de créer un quizz actu
     *
     * @Template()
     */
    public function creerAction()
    {
        $quizzActu = new QuizzActu();
        $quizzActu->setAuteur($this->getUser());

        $form = $this->createForm(new QuizzActuType(), $quizzActu);

        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($quizzActu);
                $entityManager->flush();

                $this->get('session')->setFlash('success', "Le quizz actu a bien été crée.");
                return $this->redirect($this->generateUrl('quizzActu'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Supprime un quizz actu
     *
     * @param integer $id Id de la sortie à supprimer.
     * @Template()
     */
    public function supprimerAction($id)
    {
        $quizzActu = $this->getDoctrine()->getRepository('CECActiviteBundle:QuizzActu')->find($id);
        if (!$quizzActu) throw $this->createNotFoundException('Impossible de trouver le quizz actu !');

        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->remove($quizzActu);
        $entityManager->flush();

        $this->get('session')->setFlash('success', 'Le quizz actu a bien été définitivement supprimé.');
        return $this->redirect($this->generateUrl('quizzActu'));
    }


}
