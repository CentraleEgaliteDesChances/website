<?php

namespace CEC\SecteurProjetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurProjetsBundle\Form\ProjetType;



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
	* Mise à jour du dossier d'inscription pour un projet
	*
	* @Template()
	*/
	public function uploadDossierAction()
	{
		$dossier = new Dossier();
		$form = $this->createForm(new DossierType(), $dossier);
		$request = $this->getRequest();
		if ($request->isMethod('POST'))
		{
			$form->bindRequest($request);
			if($form->isValid())
			{
				$em = $this->getDoctrine()->getEntityManager();
				$data = $form->getData();
				$projet = $data->getProjet();
				$dossier_precedent = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Dossier')->loadDossier($projet);
				if($dossier_precedent)
				{
					$em->remove($dossier_precedent);
					$em->flush();
				}
				$nom_projet = $projet->getNom();
				$dossier->setNom("Dossier d'inscription ".$nom_projet);
				$projet->setDossier($dossier);
				$em->persist($projet);
				$em->persist($dossier);
				$em->flush();
				
				
				$this->get('session')->setFlash('success', 'Le dossier a bien été mis à jour');
				return $this->redirect($this->generateUrl('description_projets'));
				
			}
		}
		
		return array( 'form' => $form->createView());
	}
	
	/**
	* Mise à jour de l'état d'ouverture des inscriptions des projets
	*
	* @Template();
	*/
	public function inscriptionsAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
		$request = $this->getRequest();
		$data = array();
		$message ='';
		
		
		if($request->isMethod('POST'))
		{
			foreach($projets as $projet)
			{
				$slug = $projet->getSlug();
				$data[$slug] = ($request->request->get($slug)=="true") ? true : false ;
				$projet->setInscriptionsOuvertes($data[$slug]);
				
				$em->persist($projet);
				$em->flush();
				
			}
			
			$this->get('session')->setFlash('success', 'L\'ouverture des inscriptions a bien été mise à jour. ');
			return $this->redirect($this->generateUrl('description_projets'));
			
		}
		
		return array('projets'=>$projets);
	}
	
	
	
}

