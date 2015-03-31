<?php

namespace CEC\SecteurProjetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurProjetsBundle\Form\ProjetType;
use CEC\SecteurProjetsBundle\Form\ReunionType;
use CEC\SecteurProjetsBundle\Form\DossierType;
use CEC\SecteurProjetsBundle\Form\AlbumType;
use CEC\SecteurProjetsBundle\Entity\Reunion;
use CEC\SecteurProjetsBundle\Entity\Dossier;
use CEC\SecteurProjetsBundle\Entity\Album;

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
	* Page de visualisation des réunions
	*
	*@Template()
	*/
	public function voirReunionAction()
	{
		$reunions = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Reunion')->findAllByDate();
		$projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
		
		return array('reunions' => $reunions, 'projets'=>$projets);
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
                return $this->redirect($this->generateUrl('liste_reunions'));
            }
        }

        return array(
            'form' => $form->createView()
        );
	}
	
	/**
	* Modifier les réunions d'information
	*
	* @Template()
	*/
	public function modifierReunionAction($id)
	{
		$reunion = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Reunion')->find($id);
		$form = $this->createForm(new ReunionType(), $reunion);
		$request = $this->getRequest();
		
		if($request->isMethod('POST'))
		{
			$form->bindRequest($request);
			if($form->isValid())
			{
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();
				
				$this->get('session')->setFlash('success', "La réunion a bien été modifiée");
                return $this->redirect($this->generateUrl('liste_reunions'));
			}
		}
		
		return array('id' => $id, 'form' => $form->createView());
	}
	
	/**
	* Supprimer une réunion d'information 
	*
	*/
	public function supprimerReunionAction($id)
	{
		$reunion = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Reunion')->findBy($id);
		if(!reunion) throw $this->createNotFoundException('Impossible de trouver la réunion !');
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($reunion);
		$em->flush();
		
		$this->get('session')->setFlash('success', "La réunion a bien été supprimée");
        return $this->redirect($this->generateUrl('liste_reunions'));
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
	
	
	/**
	* Permet d'ajouter des photos à un projet
	*
	*@Template()
	*/
	public function ajouterPhotosAction()
	{
		$album = new Album();
		$form = $this->createForm(new AlbumType(), $album);
		
		$request = $this->getRequest();
		if($request->isMethod('POST'))
		{
			$form->bindRequest($request);
			if($form->isValid())
			{
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($album);
				$images = $album->getImages();
				foreach($images as $image)
				{
					$image->setAlbum($album);
					$em->persist($image);
				}
				$em->flush();
				
				$this->get('session')->setFlash('success', 'L\'album photo a bien été créé !');
				return $this->redirect($this->generateUrl('voir_albums'));
			}
		}
		
		return array('form' => $form->createView());
	}
	
	/**
	* Affiche la liste des albums photos par projets et par année
	*
	*@Template()
	*/
	public function voirAlbumsAction()
	{
		$projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
		
		return array('projets' => $projets);
	}
	
	/**
	* Affiche le carousel des photos d'un album
	* @param integer $id Id de l'album
	*
	* @Template()
	*/
	public function voirPhotosAction($id)
	{
		$album = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Album')->find($id);
		
		return array('album' => $album);
	}
}
