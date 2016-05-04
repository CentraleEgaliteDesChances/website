<?php

namespace CEC\MembreBundle\Controller;

use CEC\MembreBundle\Form\Type\EleveType;
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
				'choices' => array('tuteur' => 'Tuteur', 'lyceen' => 'Lycéen', 'prof' => 'Enseignant')
			))
			->add('prenom', 'text')
			->add('nom', 'text')
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
							$membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->loadUserByUsername($data['prenom'] . ' ' . $data['nom']);
							break;
						case 'lyceen':
							$membre = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->loadUserByUsername($data['prenom'] . ' ' . $data['nom']);
							break;
						case 'professeur':
							$membre = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->loadUserByUsername($data['prenom'] . ' ' . $data['nom']);
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
				$this->get('session')->getFlashBag()->add('success', 'Le mot de passe de ' . $data['prenom'] . ' ' . $data['nom'] . ' a bien été réinitialisé.');
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

		$form = $this->createFormBuilder($inscrit)
			->add('prenom', 'text', array(
				'label' => 'Prénom',
				'attr' => array('autofocus' => '1', 'placeholder'=>'Prénom'),
			))
			->add('nom', 'text', array(
				'label'=>'Nom',
				'attr' => array('placeholder' => 'Nom'),
			))
			->add('mail', 'text', array(
				'label' => 'Adresse email',
				'attr' => array('placeholder' => 'Adresse Mail'),
			))
			->add('telephoneFixe', 'text', array(
				'label' => 'Numéro de téléphone fixe',
				'required' => false,
			))
			->add('telephonePortable', 'text', array(
				'label' => 'Numéro de téléphone portable',
				'required' => false,
			))
			->add('lycee', null, array(
				'label'=>'Lycée de provenance',
			))
			->add('role', 'choice', array(
				'choices' => array ('proviseur' => "Proviseur", "proviseurAdjoint" => "Proviseur Adjoint", "cpe" => "Conseiller Principal d'Education", "professeur" => "Enseignant"),
				'label' => 'Rôle dans l\'établissement'
			))
			->add('motDePasse', 'repeated', array(
				'label'=>'Mot de passe',
				'first_name' => 'Mot-de-passe',
				'second_name' => 'Confirmation',
				'type' => 'password',
			))
			->getForm();

		$request= $this->getRequest();

		if ($request->isMethod("POST"))
		{
			$form->handleRequest($request);
			if ($form->isValid())
			{
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

		$form = $this->get('form.factory')->create(EleveType::class,$eleve);

		$request= $this->getRequest();

		if ($request->isMethod("POST"))
		{
			$form->handleRequest($request);

			if ($form->isValid())
			{
				//Enregistrement en BDD
					//Génère l'username de l'élève
				$nom = $form->get('nom')->getData();
				$prenom= $form->get('prenom')->getData();
				$membresExistant = $this
					->getDoctrine()
					->getRepository('CECMembreBundle:Eleve')
					->findByUsername($nom,$prenom);
				if (count($membresExistant) > 0)
				{
					$eleve->setUsername($prenom. ' ' . $nom.(count($membresExistant)+1));
				}
				else
				{
					$eleve->setUsername($prenom . ' ' .$nom);
				}

					// Enregistre dans la BDD
				$encoder = $this->container->get('security.encoder_factory')->getEncoder($eleve);
				$motDePasse = $eleve->getMotDePasse();
				$eleve->setMotDePasse($encoder->encodePassword($motDePasse, $eleve->getSalt()));
				$em = $this->getDoctrine()->getManager();
				$em->persist($eleve);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Inscription bien effectuée.');
				$this->get('cec.mailer')->sendInscriptionEleve($eleve,$motDePasse,$_SERVER['HTTP_HOST']);
				return $this->redirect($this->generateUrl('connexion'));
			}
		}

		return $this->render('CECMembreBundle:Inscription:eleve.html.twig', array(
			'form' => $form->createView(),
		));
	}
}
