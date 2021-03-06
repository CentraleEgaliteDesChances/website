<?php

namespace CEC\MembreBundle\Controller;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;
use CEC\MembreBundle\Entity\DossierInscription;
use CEC\MembreBundle\Entity\ParentEleve;
use CEC\MembreBundle\Form\Type\EleveType;
use CEC\MembreBundle\Form\Type\ParentEleveType;
use CEC\MembreBundle\Form\Type\ProfesseurType;
use CEC\SecteurProjetsBundle\Entity\Dossier;
use CEC\TutoratBundle\Entity\GroupeEleves;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use CEC\MembreBundle\Entity\Membre;
use CEC\MembreBundle\Entity\Eleve;
use CEC\MembreBundle\Entity\Professeur;

class SecuriteController extends Controller
{
	/**
	 * Connexion au site interne.
	 * Cette page est appelé automatiquement par le composant Security de Symfony.
	 * Elle présente un formulaire permettant d'entrer un identifiant (prénom + nom) ainsi
	 * qu'un mot de passe ; un bouton connexion lance la procédure d'authentification.
	 *@Template()
	 */
	public function connexionAction()
	{
		$request = $this->getRequest();
		$session = $request->getSession();

		// Récupère les erreurs de connexion si nécessaire
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$erreur = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} else {
			$erreur = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}

		return array(
			// Dernier nom d'utilisateur entré
			'dernier_utilisateur'    => $session->get(SecurityContext::LAST_USERNAME),
			'erreur'                 => $erreur,
		);
	}

	/**
	 * Gestion des mots de passes oubliés.
	 * La page appelée contient un formulaire afin de récupérer
	 * le nom et le prénom du membre concerné.
	 * Permet de réinitialiser le mot de passe d'un membre,
	 * et de lui envoyer ses identifiants par mail.
	 *
	 * @Template()
	 */
	public function oubliAction()
	{
		$form = $this->createFormBuilder()
			->add('categorie', 'choice', array(
				'label' => 'Statut :',
				'choices' => array(
					'tuteur' => 'Tuteur',
					'lyceen' => 'Lycéen',
					'prof' => 'Enseignant',
					'parent' =>'Parent'
				)
			))
			->add('username', 'text')
			->getForm();

		$request = $this->getRequest();

		if ($request->isMethod("POST"))
		{
			$form->handleRequest($request);
			if ($form->isValid())
			{
				$data = $form->getData();

				// On checke chaque base de données suivant la catégorie
				try
				{
					switch($data['categorie'])
					{
						case 'tuteur':
							$membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->loadUserByUsername($data['username']);
							break;
						case 'lyceen':
							$membre = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->loadUserByUsername($data['username']);
							break;
						case 'prof':
							$membre = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->loadUserByUsername($data['username']);
							break;
						case 'parent':
							$membre = $this->getDoctrine()->getRepository('CECMembreBundle:ParentEleve')->loadUserByUsername($data['username']);
							break;
						default:
							break;
					}
				}catch( \Exception $e)
				{
					$form->addError(new FormError($e->getMessage()));
					return array('form' => $form->createView());

				}

				//Création d'un nouveau mot de passe aléatoire
				$motDePasse = substr(str_shuffle(MD5(microtime())), 0, 10);

				$factory = $this->get('security.encoder_factory');
				$encoder = $factory->getEncoder($membre);
				$password = $encoder->encodePassword($motDePasse, $membre->getSalt());
				$membre->setMotDePasse($password);

				$entityManager = $this->getDoctrine()->getEntityManager();
				$entityManager->persist($membre);
				$entityManager->flush();

				//Envoi du mail
				$this->get('cec.mailer')->sendOubliMdP($membre, $motDePasse, $_SERVER['HTTP_HOST']);

				//Retour à la page de connexion
				$this->get('session')->getFlashBag()->add('success', 'Le mot de passe de ' . $data['username'] . ' a bien été réinitialisé.');
				return $this->redirect($this->generateUrl('connexion'));
			}
		}

		return array(
			'form'           => $form->createView()
		);
	}

	public function inscriptionProfesseurAction()
	{
		//Creation of the form to register a new teacher
		$inscrit = new Professeur();

		$form = $this->get('form.factory')->create(ProfesseurType::class,$inscrit);

		$request= $this->getRequest();

		if ($request->isMethod("POST"))
		{
			$form->handleRequest($request);
			if ($form->isValid())
			{
				//Génère l'username du professeur
				$nom = str_replace(' ','' ,$form->get('nom')->getData());
				$prenom= str_replace(' ','' ,$form->get('prenom')->getData());
				$elevesExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Eleve')
					->findByUsername($nom,$prenom);
				$membresExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Membre')
					->findByUsername($nom,$prenom);
				$professeursExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Professeur')
					->findByUsername($nom,$prenom);
				$parentsExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:ParentEleve')
					->findByUsername($nom, $prenom);
				$count = count($elevesExistant) + count($membresExistant) + count($professeursExistant) + count($parentsExistant);
				if ($count > 0)
				{
					$inscrit->setUsername($prenom.$nom.($count + 1));
				}
				else
				{
					$inscrit->setUsername($prenom.$nom);
				}
				$inscrit->setReferent($form->get('lycee')->getData());
				//Enregistrement en BDD

				$encoder = $this->container->get('security.encoder_factory')->getEncoder($inscrit);
				$motDePasse = $inscrit->getMotDePasse();
				$inscrit->setMotDePasse($encoder->encodePassword($motDePasse, $inscrit->getSalt()));
				$em = $this->getDoctrine()->getManager();
				$em->persist($inscrit);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Inscription bien effectuée.');

				return $this->redirect($this->generateUrl('connexion'));
			}
		}


		return $this->render('CECMembreBundle:Inscription:professeur.html.twig', array(
			'form' => $form->createView(),
		));
	}

	public function inscriptionEleveAction()
	{
		//Creation of form to register a new high-school student

		$eleve = new Eleve();
		$formProfil = $this->get('form.factory')->create(EleveType::class, $eleve);
		$request = $this->getRequest();

		if ($request->isMethod("POST")) {
			$formProfil->handleRequest($request);
			if ($formProfil->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				//Enregistrement en BDD
				//Génère l'username de l'élève
                $username = "";
				$nom = str_replace(' ','' ,$formProfil->get('nom')->getData());
				$prenom= str_replace(' ','' ,$formProfil->get('prenom')->getData());
				$elevesExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Eleve')
					->findByUsername($nom, $prenom);
				$membresExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Membre')
					->findByUsername($nom, $prenom);
				$professeursExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Professeur')
					->findByUsername($nom, $prenom);
				$parentsExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:ParentEleve')
					->findByUsername($nom, $prenom);
				$count = count($elevesExistant) + count($membresExistant) + count($professeursExistant) + count($parentsExistant);
				if ($count > 0) {
					$username = $prenom . $nom . ($count + 1);
				} else {
					$username = $prenom . $nom;
				}
				$eleve->setUsername($username);
				
				//Assigne l'élève a un groupe de tutorat
				$niveau = $formProfil->get('niveau')->getData();
				$lycee = $formProfil ->get('lycee')->getData();

				$groupes = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->findByNiveau($niveau);
				$groupe = null;
				foreach ($groupes as $g) {
					if (in_array($lycee,$g->getLycees())) {
						$groupe = $g;
					};
				}
				
				if ($groupe != null) {
					$groupeMembre = new GroupeEleves();
					$groupeMembre->setAnneeScolaire(AnneeScolaire::withDate());
					$groupeMembre->setLyceen($eleve);
					$groupeMembre->setGroupe($groupe);
					$em->persist($groupeMembre);

				}



				// Enregistre dans la BDD
				$encoder = $this->container->get('security.encoder_factory')->getEncoder($eleve);
				$motDePasse = $eleve->getMotDePasse();
				$eleve->setMotDePasse($encoder->encodePassword($motDePasse, $eleve->getSalt()));
				$eleve->setDossierInscription(null);
				$em->persist($eleve);
				$em->flush();

				$this->get('session')->getFlashBag()->add('success', 'Inscription bien effectuée. Username : "'.$username.'".');
				$this->get('cec.mailer')->sendInscriptionEleve($eleve, $motDePasse, $_SERVER['HTTP_HOST']);
				return $this->redirect($this->generateUrl('connexion'));
			}
		}

		return $this->render('CECMembreBundle:Inscription:eleve.html.twig', array(
			'formProfil' => $formProfil->createView()
		));
	}

	/**
	 * Permet l'inscription d'un nouveau parent d'eleves
	 * Affiche un formulaire permettant d'entrer le nom, le prénom, l'adresse email,
	 * le numéro de téléphone du parent. Un bouton permet
	 * d'enregistrer le nouveau parent, et un bouton Annuler permet de revenir à la page de connexion.
	 *
	 */
	public function inscriptionParentAction()
	{
		//Creation of the form to register a new parent
		$parent = new ParentEleve();

		$form = $this->get('form.factory')->create(ParentEleveType::class, $parent);

		$request = $this->getRequest();

		if ($request->isMethod("POST")) {
			$form->handleRequest($request);
			if ($form->isValid()) {
				//Génère l'username de l'élève
				$nom = str_replace(' ','' ,$form->get('nom')->getData());
				$prenom= str_replace(' ','' ,$form->get('prenom')->getData());
				$elevesExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Eleve')
					->findByUsername($nom, $prenom);
				$membresExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Membre')
					->findByUsername($nom, $prenom);
				$professeursExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Professeur')
					->findByUsername($nom, $prenom);
				$parentsExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:ParentEleve')
					->findByUsername($nom, $prenom);
				$count = count($elevesExistant) + count($membresExistant) + count($professeursExistant) + count($parentsExistant);
				if ($count > 0) {
					$parent->setUsername($prenom . $nom . ($count + 1));
				} else {
					$parent->setUsername($prenom . $nom);
				}
				//Enregistrement en BDD

				$encoder = $this->container->get('security.encoder_factory')->getEncoder($parent);
				$motDePasse = $parent->getMotDePasse();
				$parent->setMotDePasse($encoder->encodePassword($motDePasse, $parent->getSalt()));
				$em = $this->getDoctrine()->getManager();
				$em->persist($parent);
				$em->flush();

				$this->get('session')->getFlashBag()->add('notice', 'Inscription bien effectuée.');
                $this->get('cec.mailer')->sendInscriptionParent($parent, $motDePasse, $_SERVER['HTTP_HOST']);
				return $this->redirect($this->generateUrl('connexion'));
			}
		}


		return $this->render('CECMembreBundle:Inscription:parent.html.twig', array(
			'form' => $form->createView(),
		));
	}
}
