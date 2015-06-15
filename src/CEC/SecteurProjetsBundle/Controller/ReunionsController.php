<?php

namespace CEC\SecteurProjetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\SecteurProjetsBundle\Form\ProjetType;
use CEC\SecteurProjetsBundle\Form\ReunionType;
use CEC\SecteurProjetsBundle\Entity\Reunion;


class ReunionsController extends Controller
{	
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
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($reunion);
                $entityManager->flush();

                // On envoie un mail à tous les élèves et profs pour les prévenir
                $this->get('cec.mailer')->sendNouvelleReunion($reunion, $_SERVER['HTTP_HOST']);

                $this->get('session')->getFlashBag()->add('success', "La réunion a bien été ajoutée");
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
		if (!$reunion) throw $this->createNotFoundException('Impossible de trouver cette réunion');
		$form = $this->createForm(new ReunionType(), $reunion);
		$request = $this->getRequest();
		
		if($request->isMethod('POST'))
		{
			$form->handleRequest($request);
			if($form->isValid())
			{
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();
				
				$this->get('session')->getFlashBag()->add('success', "La réunion a bien été modifiée");

				// On envoie un mail aux tutorés inscrits à la réunion et à tous les professeurs pour les prévenir
				$this->get('cec.mailer')->sendModifReunion($reunion, $_SERVER['HTTP_HOST']);
				
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
		
		$this->get('session')->getFlashBag()->add('success', "La réunion a bien été supprimée");
        return $this->redirect($this->generateUrl('liste_reunions'));
	}

	/**
	* Inscrire un lycéen à une réunion
	* @var integer $id Id de la réunion
	* 
	*/
	public function inscriptionReunionAction($id)
	{
		$reunion = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Reunion')->find($id);
		if(!$reunion) throw $this->createNotFoundException('La réunion demandée n\'existe pas');

		$eleve = $this->getUser();
		$reunion->addPresent($eleve);
		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();

		$this->get('session')->getFlashBag()->add('success', 'Ton inscription à la réunion a bien été prise en compte !');
		return $this->redirect($this->generateUrl('liste_reunions'));
	}

	/**
	* Désinscrire un lycéen d'une réunion
	* @var integer $id Id de la réunion
	* 
	*/
	public function desinscriptionReunionAction($id)
	{
		$reunion = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Reunion')->find($id);
		if(!$reunion) throw $this->createNotFoundException('La réunion demandée n\'existe pas');

		$eleve = $this->getUser();
		$reunion->removePresent($eleve);
		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();

		$this->get('session')->getFlashBag()->add('success', 'Ta désinscription à la réunion a bien été prise en compte !');
		return $this->redirect($this->generateUrl('liste_reunions'));
	}
}