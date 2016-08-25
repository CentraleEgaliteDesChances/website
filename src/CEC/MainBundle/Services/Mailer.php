<?php
namespace CEC\MainBundle\Services;

use Symfony\Component\Templating\EngineInterface;

use CEC\MembreBundle\Entity\Professeur;
use CEC\MembreBundle\Entity\Eleve;
use CEC\MembreBundle\Entity\Membre;

class Mailer
{
	protected $mailer;
	protected $templating;
	protected $doctrine;
	private $from = "notifications@cec-ecp.com";
	private $reply = "contact@cec-ecp.com";
	private $nameFrom = "Notifications CEC";
	private $nameRep = "Contact CEC";

	public function __construct($mailer, EngineInterface $templating, $doctrine)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->doctrine = $doctrine;
	}

	protected function sendMessage($to, $subject, $body)
	{
		$mail = \Swift_Message::newInstance();

		$mail
		->setFrom(array($this->from => $this->nameFrom))
		->setTo($to)
		->setSubject($subject)
		->setBody($body)
		->setReplyTo(array($this->reply =>$this->nameRep))
		->setContentType('text/html');

		$this->mailer->send($mail);
	}

	/**
	*
	* ################################
	* 
	* Mails dédiés aux tuteurs
	*
	* ################################
	*
	*/

	/**
	*
	* Mail d'inscription d'un nouveau tuteur
	*
	*/
	public function sendInscription($membre, $motDePasse, $baseUrl)
	{
		$subject = "Bienvenue sur le site interne de CEC !";
		$to = array($membre->getMail() => $membre->__toString());
		$body = $this->templating->render('CECMembreBundle:Mail:bienvenue.html.twig',
                            array(
                                'membre' => $membre,
                                'mot_de_passe' => $motDePasse,
                                'base_url' => $baseUrl,
                            ));

		$this->sendMessage($to, $subject, $body);
	}
	/**
	* Mail d'oubli de mot de passe
	*/
	public function sendOubliMdP($membre, $motDePasse, $baseUrl)
	{
		$subject = "Mot de passe pour le site interne de CEC";
		$to = array($membre->getMail() => $membre->__toString());
		$body = $this->templating->render('CECMainBundle:Mails:oubli.html.twig',
                            array(
                                'membre' => $membre,
                                'mot_de_passe' => $motDePasse,
                                'base_url' => $baseUrl,
                            ));


		$this->sendMessage($to, $subject, $body);
	}

	/**
	* Mail signalant à un membre q'on lui a donné les droits BURO
	*/
	public function sendPassations($membre, $baseUrl)
	{
		$to = array($membre->getMail() => $membre->__toString());
		$subject = "Nouveaux droits d'administration sur le site de CEC";
		$template = "CECMainBundle:Mails:passations.html.twig";

		$body = $this->templating->render($template, array('membre' => $membre, 'base_url'=> $baseUrl));

		$this->sendMessage($to, $subject, $body);
	}


	/**
	 *
	 * ################################
	 *
	 * Mails dédiés aux lycéens
	 *
	 * ################################
	 *
	 */

	/**
	 *
	 * Mail d'inscription d'un nouveau tutoré
	 *
	 */
	public function sendInscriptionEleve($eleve, $motDePasse, $baseUrl)
	{
		$subject = "Bienvenue sur le site de CEC !";
		$to = array($eleve->getMail() => $eleve->__toString());
		$body = $this->templating->render('CECMembreBundle:Mail:bienvenueEleve.html.twig',
			array(
				'eleve' => $eleve,
				'mot_de_passe' => $motDePasse,
				'base_url' => $baseUrl,
			));

		$this->sendMessage($to, $subject, $body);
	}

	/**
	 * ################################
	 *
	 * Mails dédiés aux parents
	 *
	 * ################################
	 */

	/**
	 *
	 * Mail de notification à un parent lorsque son enfant s'inscrit
	 *
	 */
	public function sendNotificationParentInscriptionEleve($mailParent,$eleve, $baseUrl)
	{
		$subject = "[CEC] Votre enfant vient de s'inscrire sur le site de CEC !";
		$to = array($mailParent => $mailParent);
		$body = $this->templating->render('CECMembreBundle:Mail:notificationParentInscriptionEleve.html.twig',
			array(
				'eleve' =>$eleve,
				'base_url' => $baseUrl,
			));

		$this->sendMessage($to, $subject, $body);
	}
	

	/**
	 *
	 * Mail d'inscription d'un nouveau parent
	 *
	 */
	public function sendInscriptionParent($parent, $motDePasse, $baseUrl)
	{
		$subject = "Bienvenue sur le site de CEC !";
		$to = array($parent->getMail() => $parent->__toString());
		$body = $this->templating->render('CECMembreBundle:Mail:bienvenueParent.html.twig',
			array(
				'parent' => $parent,
				'mot_de_passe' => $motDePasse,
				'base_url' => $baseUrl,
			));

		$this->sendMessage($to, $subject, $body);
	}

	public function sendParentSortieCreee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie, $baseUrl)
	{
		$subject = "Une sortie CEC vient d'être créée !";
		$to = array();

		$parents = $this->doctrine->getRepository('CECMembreBundle:ParentEleve')->findByCheckMail(true);



		foreach($parents as $p)
		{
			$to[$p->getMail()] = $p->__toString();
		}


		$body = $this->templating->render('CECMembreBundle:Mail:notificationParentCreationSortie.html.twig', array('sortie' => $sortie, 'base_url'=> $baseUrl));

		$this->sendMessage($to, $subject, $body);

	}
	/**
	* ################################
	*
	* Mails dédiés aux projets 
	*
	* ################################
	*/

	/**
	* Mail prévenant les élèves et les profs de la création d'un nouveal album photo
	*
	*/
	public function sendNouvelAlbum($album, $baseUrl)
	{
		$subject = $album->getProjet()->getNom()." : Nouvel album pour l'édition ".$album->getAnnee()." sur le site de CEC !";
		$to = array();

		$eleves = $this->doctrine->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->doctrine->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);
		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->__toString();
		}

		$template = "CECMainBundle:Mails:nouvelAlbum.html.twig";

		$body = $this->templating->render($template, array('album' => $album, 'base_url' => $baseUrl));
	}

	/**
	*
	* Mail qui prévient les tutorés et les professeurs référents de l'ouverture des inscriptions pour un projet
	*/
	public function sendInscriptionsOuvertes($projet, $baseUrl)
	{
		$subject = "Les inscriptions pour ".$projet->getNom()." viennent d'être ouvertes !";
		$to = array();

		$eleves = $this->doctrine->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->doctrine->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter($professeurs, function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); });

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->__toString();
		}

		$template = "CECMainBundle:Mails:inscriptionsOuvertes.html.twig";

		$body = $this->templating->render($template, array('projet' => $projet, 'base_url' => $baseUrl));

		$this->sendMessage($to, $subject, $body);
	}

	/**
	*
	* Mail envoyé à chaque lycéen lorsqu'il est inscrit à un projet
	*/
	public function sendInscrit($projet, $lyceen, $baseUrl)
	{
		$subject= "Ton inscription a été retenue pour le projet ".$projet->getNom()."!";
		$to = array($lyceen->getMail() => $lyceen->_toString());

		$template = "CECMainBundle:Mails.inscrit.html.twig";

		$body = $this->templating->render($template, array('projet' => $projet, 'lyceen' => $lyceen, 'base_url' => $baseUrl));

		$this->sendMessage($to, $subject, $body);
	}


	/**
	* ################################
	*
	* Mails dédiés aux réunions
	*
	* ################################
	*/

	/**
	* Mail envoyé aux tutorés et aux professeurs référents pour les prévenir de la création d'une nouvelle réunion
	*
	*/
	public function sendNouvelleReunion($reunion, $baseUrl)
	{
		$subject = "Nouvelle réunion d'informations pour le projet ".$reunion->getProjet()->getNom();

		$to = array();

		$eleves = $this->doctrine->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->doctrine->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter($professeurs, function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); });

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->__toString();
		}

		$template = "CECMainBundle:Mails:nouvelleReunion.html.twig";

		$body = $this->templating->render($template, array('reunion' => $reunion, 'base_url' => $baseUrl));

		$this->sendMessage($to, $subject, $body);

	}

	/**
	* Mail envoyé aux tutorés  et aux professeurs référents pour les prévenir des changements
	*/
	public function sendModifReunion($reunion, $baseUrl)
	{
		$subject = "Une réunion concernant le projet ".$reunion->getProjet()->getNom()." a été modifiée.";

		$to = array();

		$eleves = $this->doctrine->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->doctrine->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter($professeurs, function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); });

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->__toString();
		}

		$template = "CECMainBundle:Mails:modifReunion.html.twig";

		$body = $this->templating->render($template, array('reunion' => $reunion, 'base_url' => $baseUrl));

		$this->sendMessage($to, $subject, $body);
	}

	/**
	*
	* Mail envoyé aux tutorés inscrits et aux professeurs référents pour les prévenir de la suppression d'une réunion
	*
	*/
	public function sendReunionSupprimee($reunion, $baseUrl)
	{
		$subject = "Une réunion concernant le projet ".$reunion->getProjet()->getNom()." a été supprimée.";

		$to = array();

		$eleves = $reunion->getPresents();
		$professeurs = $this->doctrine->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter($professeurs, function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); });

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->__toString();
		}

		$template = "CECMainBundle:Mails:reunionSupprimee.html.twig";

		$body = $this->templating->render($template, array('reunion' => $reunion, 'base_url' => $baseUrl));

		$this->sendMessage($to, $subject, $body);
	}

	/**
	* ################################
	*
	* Mails dédiés aux sorties
	*
	* ################################
	*/
	
	/**
	* Envoie un message à tous les lyceens et aux référents quand une sortie a été modifiée
	*
	*/
	public function sendSortieModifiee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie, $baseUrl)
	{
		$subject = "Une sortie CEC a été modifiée !";
		$to = array();

		$lyceens = $this->doctrine->getEntityManager()->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->doctrine->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter($professeurs, function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); });

		$membres = array_merge($lyceens, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->__toString();
		}

		$template = "CECMainBundle:Mails:sortieModifiee.html.twig";
		
		
		$body = $this->templating->render($template, array('sortie' => $sortie, 'base_url'=> $baseUrl));
		
		$this->sendMessage($to, $subject, $body);
		
	}

	/**
	* Envoie un message à tous les lyceens et aux profs référents quand une sortie a été créée
	*
	*/
	public function sendEleveSortieCreee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie, $baseUrl)
	{
		$subject = "Une sortie CEC vient d'être créée !";
		$to = array();

		$lyceens = $this->doctrine->getEntityManager()->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->doctrine->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);


		// On ne prévient que les professeurs référents.
		$professeurs = array_filter($professeurs, function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); });

		$membres = array_merge($lyceens, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->__toString();
		}

		$template = "CECMainBundle:Mails:sortieCreee.html.twig";
		
		$body = $this->templating->render($template, array('sortie' => $sortie, 'base_url'=> $baseUrl));
		
		$this->sendMessage($to, $subject, $body);
		
	}
	
	/**
	* Envoie un message à tous les lyceens inscrits à une sortie et aux profs référents quand elle a été supprimée
	*
	*/
	public function sendSortieSupprimee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie, $baseUrl)
	{
		$subject = "Une sortie CEC a été supprimée !";
		$to = array();
		$lyceens = $sortie->getLyceens();

		$professeurs = $this->doctrine->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);
		// On ne prévient que les professeurs référents.
		$professeurs = array_filter($professeurs, function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); });

		$membres = array_merge($lyceens, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->__toString();
		}
		
		$template = "CECMainBundle:Mails:sortieSupprimee.html.twig";

		$body = $this->templating->render($template, array('sortie' => $sortie, 'base_url'=> $baseUrl));
		
		$this->sendMessage($to, $subject, $body);
		
	}
	
	/**
	* Envoie un message à cec.sortie@gmail.com quand un lycéen se désinscrit d'une sortie
	*
	*/
	public function sendLyceenDesinscrit(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie, $baseUrl)
	{
		$subject = "Un lycéen s'est désinscrit d'une sortie à venir !";
		$to = array("cec.sortie@gmail.com" => "Secteur Sorties CEC");
		$template = "CECMainBundle:Mails:desinscrit.html.twig";
		
		$body = $this->templating->render($template, array('sortie'=> $sortie, 'base_url'=> $baseUrl));
		
		$this->sendMessage($to, $subject, $body);
	}

	
	/**
	* Envoie un mail de désinscription au lycéen quand il est désinscrit par un tuteur
	*
	*/
	public function sendDesinscription(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie, $eleve)
	{
		$subject = "Confirmation de ta désinscription à une sortie CEC";
		$template = "CECMainBundle:Mails:desinscription.html.twig";
		$to = array($eleve->getMail() => $eleve->__toString());
		
		$body = $this->templating->render($template, array('sortie'=>$sortie));
		
		$this->sendMessage($to, $subject, $body);
	}
}
