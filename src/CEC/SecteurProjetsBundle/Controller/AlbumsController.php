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

class AlbumsController extends Controller
{
	/**
	* Permet d'ajouter des photos à un projet
	*
	*@Template()
	*/
	public function ajouterPhotosAction($id)
	{
		if($id==0)
		{
			$album = new Album();
		}else{
			$album = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Album')->find($id);
		}
		
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

				// Envoi d'un mail à tous les tutorés et tous les profs
				$this->get('cec.mailer')->sendNouvelAlbum($album, $_SERVER['HTTP_HOST']);
				
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

	/**
	* Page permettant le choix d'un album à modifier
	*
	* @Template()
	*/
	public function gererAlbumsAction()
	{
		$projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
		
		return array('projets' => $projets);
	}

	/**
	* Méthode pour modifier un album
	* @param integer $id Id de l'album à modifier
	*
	*@Template()
	*/
	public function gererAlbumAction($id, $image_id)
	{
		$album = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Album')->find($id);
		if (!$album) throw $this->createNotFoundException('Impossible de trouver cet album');

		if($image_id <> 0)
		{
			$image = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Image')->find($image_id);
			if (!$image) throw $this->createNotFoundException('Impossible de trouver cette image');

			$album->removeImage($image);
			$em->flush();

			$this->get('session')->setFlash('success', 'La photo a bien été supprimée !');
			$this->redirect($this->generateUrl('gerer_album', array('id'=>$id)));
		}

		$infosalbum = new Album();
		$infosalbum->setProjet($album->getProjet());
		$infosalbum->setAnnee($album->getAnnee());
		$form = $this->createForm(new AlbumType(), $infosalbum, array('disabled'=>true));
		
		$request = $this->getRequest();
		if($request->isMethod('POST'))
		{
			$form->bindRequest($request);
			if($form->isValid())
			{
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($infosalbum);
				$images = $infosalbum->getImages();
				if(!$images) throw $this->createNotFoundException('Pas de photos dans le formulaire !');

				foreach($images as $image)
				{
					$album->addImage($image);
					$image->setAlbum($album);
					$em->persist($image);
				}
				$em->flush();
				
				$this->get('session')->setFlash('success', 'L\'album photo a bien été modifié !');
				return $this->redirect($this->generateUrl('gerer_albums'));
			}
		}
		return array('album'=>$album, 'form'=>$form->createView());
	}
}