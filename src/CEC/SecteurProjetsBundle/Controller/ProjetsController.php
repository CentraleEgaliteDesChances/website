<?php

namespace CEC\SecteurProjetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurProjetsBundle\Form\ProjetType;
use CEC\SecteurProjetsBundle\Form\ReunionType;
use CEC\SecteurProjetsBundle\Entity\Reunion;

class ProjetsController extends Controller
{
	/**
	* Page de présentation du Pole Projets
	*
	* @Template()
	*/
    public function voirAction()
    {
        $projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
		if(!$projets) throw $this->createNotFoundException('Aucun projet trouvé !');
		
		return array('projets' => $projets);
    }
	
	/**
	* Menu Pôle Projets
	*
	* @Template()
	*/
	public function menuAction($request)
	{
		return array('request'=>$request);
	}
	
	/**
	* Page de présentation d'un projet
	*
	* @Template()
	*/
	public function voirProjetAction($slug)
	{

		$projet = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->loadProjet($slug);
		return array('projet' => $projet);
	}
	
	/**
	* Page d'édition d'un projet
	*
	* @Template()
	*/
	public function editerAction($slug)
	{
		$projet = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->loadProjet($slug);
		if (!$projet) throw $this->createNotFoundException('Impossible de trouver ce projet');
		
		$form = $this->createForm(new ProjetType(), $projet);
		
		$request = $this->getRequest();
		
		if($request->isMethod('POST'))
		{
			$form->bindRequest($request);
			
			if($form->isValid())
			{
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($projet);
				$em->flush();
				$this->get('session')->setFlash('success','Le projet a bien été modifié !');
				return $this->redirect($this->generateUrl('description_projet', array('slug' =>$slug)));
			}
		}
		
		return $this->render('CECSecteurProjetsBundle:Projets:editer.html.twig', array('form'=> $form->createView()));
	}
	
	/**
	* Créer une réunion d'informations pour un projet
	*
	* @Template()
	*/
	public function creerReunionAction()
	{
		$reunion = new Reunion();
        $form = $this->createForm(new ReunionType(), $reunion);
        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($reunion);
                $entityManager->flush();

                $this->get('session')->setFlash('success', "La réunion a bien été ajoutée");
                return $this->redirect($this->generateUrl('description_projets'));
            }
        }

        return array(
            'form' => $form->createView()
        );
	}
}
