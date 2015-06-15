<?php

namespace CEC\SecteurProjetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\SecteurProjetsBundle\Entity\ProjetEleve;
use CEC\SecteurProjetsBundle\Entity\Projet;
use CEC\SecteurProjetsBundle\Entity\Dossier;
use CEC\MembreBundle\Entity\Professeur;
use CEC\MembreBundle\Entity\Eleve;
use CEC\MembreBundle\Entity\Secteur;

use CEC\SecteurProjetsBundle\Form\ProjetType;
use CEC\SecteurProjetsBundle\Form\DossierType;
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
		$projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
		return array('request'=>$request, 'projets' =>$projets);
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
			$form->handleRequest($request);
			
			if($form->isValid())
			{
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($projet);
				$em->flush();
				$this->get('session')->getFlashBag()->add('success','Le projet a bien été modifié !');
				return $this->redirect($this->generateUrl('description_projet', array('slug' =>$slug)));
			}
		}
		
		return array('form'=> $form->createView(),
					 'ajouter_lyceen_form'=>$ajouterLyceenForm->createView(),
					 'projet' => $projet,
					 'lyceens' => $lyceens);
	}

	/**
	* Permet de créer un nouveau projet
	*
	* @Template()
	*/
	public function creerAction()
	{
		$projet = new Projet();
		$projetForm = $this->createForm(new ProjetType());

		$request = $this->getRequest();
		
		if($request->isMethod('POST'))
		{
			$form->handleRequest($request);
			
			if($form->isValid())
			{
				$em = $this->getDoctrine()->getEntityManager();

				// On crée le slug pour notre projet pour les URLS

				// replace non letter or digits by -
				$slug = preg_replace('~[^\\pL\d]+~u', '-', $projet->getNom());
				// trim
				$slug = trim($slug, '-');

				// transliterate
				$slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);

				// lowercase
				$slug = strtolower($slug);

				// remove unwanted characters
				$slug = preg_replace('~[^-\w]+~', '', $slug);

				if (empty($slug))
				{
					throw $this->createNotFoundException('Nom du projet incompatible');
				}

				$projet->setSlug($slug);

				// On crée le secteur associé

				$nomSecteur = 'Secteur '.$projet->getNom();
				$secteur = new Secteur();
				$secteur->setNom($nomSecteur);

				// On enregistre le tout en BDD
				$em->persist($secteur);
				$em->persist($projet);
				$em->flush();
				$this->get('session')->getFlashBag()->add('success','Le projet a bien été créé !');
				return $this->redirect($this->generateUrl('description_projet', array('slug' =>$projet->getSlug())));
			}
		}

		return array('form' => $projetForm->createView());

	}
	
	/**
	* Mise à jour du dossier d'inscription pour un projet
	*
	* @Template()
	*/
	public function uploadDossierAction()
	{
		$dossier = new Dossier();

		$projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
		$em = $this->getDoctrine()->getEntityManager();
		$defaultData = array('message' => 'Type your message here');
	    $form = $this->createFormBuilder($defaultData)
	        ->add('projet', 'entity', array(
	        	'class' => 'CEC\SecteurProjetsBundle\Entity\Projet',
	        	'property' => 'nom',
	        	'label' => 'Projet concerné',
	        	'label_attr' => array('class' => 'col-md-3 control-label')
	        	))
	        ->add('dossier', 'file', array(
	        	'label' => 'Fichier PDF du dossier',
	        	'label_attr' => array('class' => 'col-md-3 control-label')))
	        ->getForm();

	    $request = $this->getRequest();
	    if($request->isMethod('POST'))
	    {
		    $form->handleRequest($request);

		    if ($form->isValid()) 
		    {
	        
	        	$data = $form->getData();
				$projet = $data['projet'];
				$dossier_precedent = $projet->getDossier();

				// S'il y avait un dossier précédent, on s'en débarasse
				if($dossier_precedent)
				{
					$projet->setDossier(null);
					$em->remove($dossier_precedent);
					$em->flush();
				}
				$nom_projet = $projet->getNom();
				$dossier->setFile($data['dossier']);
				$dossier->setNom("Dossier d'inscription ".$nom_projet);
				$projet->setDossier($dossier);

				$em->persist($projet);
				$em->persist($dossier);
				$em->flush();
				
				
				$this->get('session')->getFlashBag()->add('success', 'Le dossier a bien été mis à jour');
				return $this->redirect($this->generateUrl('description_projets'));

			}
		}

		return array('form' => $form->createView(), 'projets' => $projets);
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
				$etat = $projet->getInscriptionsOuvertes();

				$slug = $projet->getSlug();
				$data[$slug] = ($request->request->get($slug)=="true") ? true : false ;
				$projet->setInscriptionsOuvertes($data[$slug]);

				// Si les inscriptions viennent d'être ouvertes
				if($data[$slug] and !$etat)
					$this->get('cec.mailer')->sendInscriptionsOuvertes($projet, $_SERVER['HTTP_HOST']);
				
				$em->persist($projet);
				$em->flush();
				
			}
			
			$this->get('session')->getFlashBag()->add('success', 'L\'ouverture des inscriptions a bien été mise à jour. ');
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
            $this->get('session')->getFlashBag()->add('error', 'Merci de spécifier un lycéen à ajouter.');
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
        
        // On envoie un mail au lycéen pour le prévenir de son acceptation au projet
        $this->get('cec.mailer')->sendInscrit($projet, $lyceen, $_SERVER['HTTP_HOST']);

        return $this->redirect($this->generateUrl('editer_projet', array('slug' => $projet->getSlug())));
    }
	
	/**
    *
    * Affiche la liste des participations aux projets d'un élève par année scolaire
    * @param integer $lyceen : id du lycéen
    *
    * @Template()
    */
    public function participationProjetsAction($lyceen)
    {
        $lyceen = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lyceen !');

        $projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
        $data = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:ProjetEleve')->findByLyceen($lyceen);
        

        $anneesScolaires = array();
        $participationParAnnee = array();

        if($data != null)
        {
	        foreach($data as $projetEleve)
	        {
	            $date =  $projetEleve->getAnneeScolaire();

	            $projet = $projetEleve->getProjet();

	            if(!in_array($date, $anneesScolaires))
	            {
	                $anneesScolaires[] = $date;
	            }

	            if(!array_key_exists($date->afficherAnnees(), $participationParAnnee))
	            {
	                $participationParAnnee[$date->afficherAnnees()]= array($projet);
	            }
	            else
	            {
	            	$participationParAnnee[$date->afficherAnnees()][] = $projet;
	            }

	        }
	

	        usort($anneesScolaires, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
	        if ($annee == $autreAnnee) return 0;
	        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? 1 : -1;
	        });
	    }

        return array(
                     'eleve' => $lyceen,
                     'projets' => $projets,
                     'participationParAnnee' => $participationParAnnee,
                     'anneesScolaires' => $anneesScolaires);


    }

    /**
    *
    * Affiche les participations aux projets des élèves d'un lycée
    *
    * @param integer $lycee : id du lycée cherché
    *
    * @Template()
    */
    public function participationProjetsLyceeAction($lycee)
    {
        $lycee = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->find($lycee);
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycee !');

        $projets = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:Projet')->findAll();
        $lyceens = $lycee->getLyceens();
        $anneesScolaires = array();

 		// Tableau triant les participations par lycéen, par année Scolaire, par projet
        $participations = array();

        foreach($lyceens as $lyceen)
        {
        	$participations[$lyceen->getId()] = array();
            $projetEleves = $this->getDoctrine()->getRepository('CECSecteurProjetsBundle:ProjetEleve')->findByLyceen($lyceen);

            foreach($projetEleves as $projetEleve)
            {
            	$annee = $projetEleve->getAnneeScolaire();
            	$projet = $projetEleve->getProjet();

            	if(!in_array($annee, $anneesScolaires))
            		$anneesScolaires[] = $annee;

            	$participations[$lyceen->getId()][$annee->afficherAnnees()][] = $projet;
            }
        }

        usort($anneesScolaires, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? 1 : -1;
        });

        return array('anneesScolaires' => $anneesScolaires, 'participations' => $participations, 'projets' => $projets, 'lycee' => $lycee);

    }
}

