<?php

namespace CEC\SecteurProjetsBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use CEC\SecteurSortiesBundle\Entity\Sortie;

class ReunionsPlanningEventListener
{
    private $entityManager;
    private $router;

    public function __construct(EntityManager $entityManager, Router $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $dateDebut = $calendarEvent->getStartDatetime();
        $dateFin = $calendarEvent->getEndDatetime();

        // On récupère les séances correspondant aux dates voulues
        $reunions = $this->entityManager
                        ->getRepository('CECSecteurProjetsBundle:Reunion')
                        ->findAllBetweenDates($dateDebut, $dateFin);

        foreach($reunions as $reunion)
        {
            // On crée l'event
            $event = new EventEntity('Reunion','categorie_reunions', $reunion->retreiveDateDebut(), $reunion->retreiveDateFin());

            $titre = $reunion->getNom();
            $event->setTitle('Reunion : ' . $titre);
            $event->setBgColor('#0000dd');
            $event->setUrl($this->router->generate('liste_reunions'));

            $calendarEvent->addEvent($event);
        }
    }
}
