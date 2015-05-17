<?php
namespace CEC\MainBundle\Services;

use Symfony\Component\Templating\EngineInterface;

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
		$to = array($membre->getEmail() => $membre->__toString());
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
		$to = array($membre->getEmail() => $membre->_toString());
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
	public function sendPassations($membre)
	{
		$to = array($membre->getEmail() => $membre->_toString());
		$subject = "Nouveaux droits d'administration sur le site de CEC";
		$template = "CECMainBundle:Mails:passations.html.twig";

		$body = $this->templating->render($template, array('membre' => $membre));

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

		$eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);
		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->_toString();
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

		$eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter(function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); }, $professeurs);

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->_toString();
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

		$eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter(function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); }, $professeurs);

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->_toString();
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

		$eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findByCheckMail(true);
		$professeurs = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter(function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); }, $professeurs);

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->_toString();
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
		$professeurs = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter(function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); }, $professeurs);

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->_toString();
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
	public function sendSortieModifiee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
	{
		$subject = "Une sortie CEC a été modifiée !";
		$to = array();

		$lyceens = $this->doctrine->getEntityManager()->getRepository('CECMembreBundle:Eleve')->findByCheckmail(true);
		$professeurs = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter(function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); }, $professeurs);

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->_toString();
		}

		$template = "CECMainBundle:Mails:sortieModifiee.html.twig";
		
		
		$body = $this->templating->render($template, array('sortie' => $sortie));
		
		$this->sendMessage($to, $subject, $body);
		
	}

	/**
	* Envoie un message à tous les lyceens et aux profs référents quand une sortie a été créée
	*
	*/
	public function sendSortieCreee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
	{
		$subject = "Une sortie CEC vient d'être créée !";
		$to = array();

		$lyceens = $this->doctrine->getEntityManager()->getRepository('CECMembreBundle:Eleve')->findByCheckmail(true);
		$professeurs = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);

		// On ne prévient que les professeurs référents.
		$professeurs = array_filter(function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); }, $professeurs);

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->_toString();
		}

		$template = "CECMainBundle:Mails:sortieCreee.html.twig";
		
		$body = $this->templating->render($template, array('sortie' => $sortie));
		
		$this->sendMessage($to, $subject, $body);
		
	}
	
	/**
	* Envoie un message à tous les lyceens inscrits à une sortie et aux profs référents quand elle a été supprimée
	*
	*/
	public function sendSortieSupprimee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
	{
		$subject = "Une sortie CEC a été supprimée !";
		$to = array();
		$nom = $sortie->getNom();
		$lyceens = $sortie->getLyceens();

		$professeurs = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->findByCheckMail(true);
		// On ne prévient que les professeurs référents.
		$professeurs = array_filter(function(Professeur $p) { return (in_array('ROLE_PROFESSEUR_REFERENT', $p->getRoles())); }, $professeurs);

		$membres = array_merge($eleves, $professeurs);

		foreach($membres as $m)
		{
			$to[$m->getMail()] = $m->_toString();
		}
		
		$template = "CECMainBundle:Mails:sortieSupprimee.html.twig";

		$body = $this->templating->render($template, array('sortie' => $sortie));
		
		$this->sendMessage($to, $subject, $body);
		
	}
	
	/**
	* Envoie un message à cec.sortie@gmail.com quand un lycéen se désinscrit d'une sortie
	*
	*/
	public function sendLyceenDesinscrit(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
	{
		$subject = "Un lycéen s'est désinscrit d'une sortie à venir !";
		$to = array("cec.sortie@gmail.com" => "Secteur Sorties CEC");
		$template = "CECMainBundle:Mails:desinscrit.html.twig";
		
		$body = $this->templating->render($template, array('sortie'=> $sortie));
		
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
		$to = array($eleve->getMail() => $eleve->_toString());
		
		$body = $this->templating->render($template, array('sortie'=>$sortie));
		
		$this->sendMessage($to, $subject, $body);
	}
}