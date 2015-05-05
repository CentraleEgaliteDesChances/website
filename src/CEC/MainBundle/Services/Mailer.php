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
	* Envoie un message à tous les lyceens quand une sortie a été modifiée
	*
	*/
	public function sendSortieModifiee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
	{
		$subject = "Une sortie CEC a été modifiée !";
		$to = array();
		$lyceens = $this->doctrine->getEntityManager()->getRepository('CECMembreBundle:Eleve')->findAll();
		foreach($lyceens as $lyceen)
		{
			$to[] = $lyceen->getMail();
		}
		$template = "CECMainBundle:Mails:sortieModifiee.html.twig";
		
		
		$body = $this->templating->render($template, array('sortie' => $sortie));
		
		$this->sendMessage($to, $subject, $body);
		
	}
	
	/**
	* Envoie un message à tous les lyceens quand une sortie a été créée
	*
	*/
	public function sendSortieCreee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
	{
		$subject = "Une sortie CEC vient d'être créée !";
		$to = array();
		$lyceens = $this->doctrine->getEntityManager()->getRepository('CECMembreBundle:Eleve')->findAll();
		foreach($lyceens as $lyceen)
		{
			$to[] = $lyceen->getMail();
		}
		$template = "CECMainBundle:Mails:sortieCreee.html.twig";
		
		$body = $this->templating->render($template, array('sortie' => $sortie));
		
		$this->sendMessage($to, $subject, $body);
		
	}
	
	/**
	* Envoie un message à tous les lyceens inscrits à une sortie quand elle a été supprimée
	*
	*/
	public function sendSortieSupprimee(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
	{
		$subject = "Une sortie CEC a été supprimée !";
		$to = array();
		$nom = $sortie->getNom();
		$lyceens = $sortie->getLyceens();
		foreach($lyceens as $lyceen)
		{
			$to[] = $lyceen->getMail();
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
		$to = "cec.sortie@gmail.com";
		$template = "CECMainBundle:Mails:desinscrit.html.twig";
		
		$body = $this->templating->render($template, array('sortie'=> $sortie));
		
		$this->sendMessage($to, $subject, $body);
	}
	
	/**
	* Envoie un mail de confirmation au lycéen pour son inscription à une sortie
	*
	*/
	public function sendInscription(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie, $to)
	{
		$subject = "Confirmation de ton inscription à une sortie CEC";
		$template = "CECMainBundle:Mails:inscription.html.twig";
		
		$body = $this->templating->render($template, array('sortie'=>$sortie));
		
		$this->sendMessage($to, $subject, $body);
	}
	
	/**
	* Envoie un mail de confirmation au lycéen pour sa désinscription d'une sortie
	*
	*/
	public function sendDesinscription(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie, $to)
	{
		$subject = "Confirmation de ta désinscription à une sortie CEC";
		$template = "CECMainBundle:Mails:desinscription.html.twig";
		
		$body = $this->templating->render($template, array('sortie'=>$sortie));
		
		$this->sendMessage($to, $subject, $body);
	}
}