<?php

namespace CEC\SecteurProjetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\SecteurProjetsBundle\Entity\ProjetEleve;

use CEC\SecteurProjetsBundle\Form\ProjetType;
use CEC\SecteurProjetsBundle\Form\AjouterLyceenType;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;



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

		$lyceens = $projet->getInscritsParAnnee()->toArray();
        $lyceens = array_filter($lyceens, function(ProjetEleve $e){
            return ($e->getAnneeScolaire() == AnneeScolaire::withDate());
        });
        $lyceens = array_map(function(ProjetEleve $e){return $e->getLyceen();}, $lyceens);
		
		$form = $this->createForm(new ProjetType(), $projet);
		$ajouterLyceenForm = $this->createForm(new AjouterLyceenType($projet));
		
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
		
		return array('form'=> $form->createView(),
					 'ajouter_lyceen_form'=>$ajouterLyceenForm->createView(),
					 'projet' => $projet,
					 'lyceens' => $lyceens);
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
     * Retire un lycéen du projet.
     *
     * @param string $slug: slug du projet
     * @param integer $lyceen: id du lycéen
     */
    public function supprimerLyceenAction($slug, $lyceen)
    {
        $anneeScolaire = AnneeScolaire::withDate();
        $projet = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findOneBySlug($slug);
        if (!$projet) throw $this->createNotFoundException('Impossible de trouver le projet !');
            
        $lyceen = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        $projetLyceen = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:ProjetEleve')->findOneBy(array('projet' => $projet, 'lyceen' => $lyceen, 'anneeScolaire' => $anneeScolaire));
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($projetLyceen);
        
        $em->flush();
        return $this->redirect($this->generateUrl('editer_projet', array('slug' => $projet->getSlug())));
    }
    
    /**
     * Ajoute un lycéen au projet
     *
     * @param string $slug: slug du projet
     * @param integer $lyceen: id du lycéen — Variable POST
     */
    public function ajouterLyceenAction($slug)
    {
        $projet = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findOneBySlug($slug);
        if (!$projet) throw $this->createNotFoundException('Impossible de trouver le projet!');
        // Récupère le lycéen
        $ajouterLyceenType = new AjouterLyceenType($projet);    // Permet de trouver le nom du formulaire — Attention, ne doit pas
                                                  // se confondre avec le nom de la route, ajouter_lyceen !
        $data = $this->getRequest()->get($ajouterLyceenType->getName());
        if (array_key_exists('lyceen', $data))
        {
            $lyceen = $data['lyceen'];
        } else {
            $this->get('session')->setFlash('error', 'Merci de spécifier un lycéen à ajouter.');
            return $this->redirect($this->generateUrl('editer_projet', array('projet' => $projet->getSlug())));
        }
        $lyceen = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        
        $projetLyceen = new ProjetEleve();
        $projetLyceen->setProjet($projet);
        $projetLyceen->setLyceen($lyceen);
        $projetLyceen->setAnneeScolaire(AnneeScolaire::withDate());
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($projetLyceen);
        $em->flush();
        
        return $this->redirect($this->generateUrl('editer_projet', array('slug' => $projet->getSlug())));
    }
	
	
	
}

