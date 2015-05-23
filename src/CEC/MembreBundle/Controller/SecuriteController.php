<?php

namespace CEC\MembreBundle\Controller;

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
            ->add('prenom', 'text')
            ->add('nom', 'text')
            ->getForm();
        
        $request = $this->getRequest();

        if ($request->isMethod("POST"))
        {            
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $data = $form->getData();

                // On checke chaque base de données au fur et a mesure
                try
                {
                	$membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->loadUserByUsername($data['prenom'] . ' ' . $data['nom']);
                }catch(\Exception $e)
                {
                	try
                	{
                		$membre = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->loadUserByUsername($data['prenom'] . ' ' . $data['nom']);
                	}catch(\Exception $e)
                	{
                		try
                		{
                			$membre = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->loadUserByUsername($data['prenom'] . ' ' . $data['nom']);
                		}catch( \Exception $e)
                		{
                			$form->addError(new FormError($e->getMessage()));
	                    		return array('form' => $form->createView());
                		}
                	}
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
                $this->get('session')->setFlash('success', 'Le mot de passe de ' . $data['prenom'] . ' ' . $data['nom'] . ' a bien été réinitialisé.');
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
			$inscrit->setDateCreation(new \DateTime('now'));
			$inscrit->setDateModification(new \DateTime('now'));
			$inscrit->setRoles(array('ROLE_PROFESSEUR'));
			$inscrit->setReferent(false);

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
            $form->bindRequest($request);
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
			$eleve->setDateCreation(new \DateTime('now'));
			$eleve->setDateModification(new \DateTime('now'));
			$eleve->setRoles(array('ROLE_ELEVE'));
			$eleve->setDelegue(null);

			$form = $this->createFormBuilder($eleve)
				->add('prenom', 'text', array(
					'label' => 'Prénom',
					'attr' => array('autofocus' => '1', 'placeholder'=>'Prénom'),
				))
				->add('nom', 'text', array(
					'label'=>'Nom',
					'attr' => array('placeholder' =>'Nom'),
				))
				->add('datenaiss', null, array(
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array('placeholder' => 'JJ/MM/AAAA'),
				))
				->add('mail', 'text', array(
					'label' => 'Adresse email',
					'attr' => array('placeholder' => 'Adresse Mail'),
				))
				->add('telephone', null, array('label' => 'Numéro de téléphone personnel', 'required' => false
				))
				->add('telephoneParent', null, array('label'=>'Numéro de téléphone du(des) parent(s)'
				))
				->add('nomPere', null, array('label' => 'Nom du père (optionnel)', 'required' => false, 'attr' => array('placeholder' => 'Prénom Nom')
				))
				->add('nomMere', null, array('label' => 'Nom de la mère (optionnel)', 'required' => false, 'attr' => array('placeholder' => 'Prénom Nom')
				))
				->add('adresse', null, array('label' => 'Adresse du domicile'
				))
				->add('codePostal', null, array('label' => 'Code Postal'
				))
				->add('ville', null, array('label' => 'Ville'
				))
				->add('lycee', null, array(
					'label'=>'Lycée de provenance',
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
            $form->bindRequest($request);
		
			if ($form->isValid())
			{
			//Enregistrement en BDD

			$encoder = $this->container->get('security.encoder_factory')->getEncoder($eleve); 
			$motDePasse = $eleve->getMotDePasse();
			$eleve->setMotDePasse($encoder->encodePassword($motDePasse, $eleve->getSalt()));
			$em = $this->getDoctrine()->getManager();
			$em->persist($eleve);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Inscription bien effectuée.');

			return $this->redirect($this->generateUrl('connexion'));
			}
		}

        return $this->render('CECMembreBundle:Inscription:eleve.html.twig', array(
            'form' => $form->createView(),
        ));
	}
}
