<?php

namespace CEC\CalendrierBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use CEC\SecteurSortiesBundle\Entity\Sortie;

class SortiesPlanningEventListener
{
    private $entityManager;
    private $router;

    public function __construct(EntityManager $entityManager, Router $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function loadEvents2(CalendarEvent $calendarEvent)
    {
        $dateDebut = $calendarEvent->getStartDatetime();
        $dateFin = $calendarEvent->getEndDatetime();

        // On récupère les séances correspondant aux dates voulues
        $sorties = $this->entityManager
                        ->getRepository('CECSecteurSortiesBundle:Sortie')
                        ->findAllBetweenDates($dateDebut, $dateFin);

        foreach($sorties as $sortie)
        {
            // On crée l'event
            $event = new EventEntity('Sortie','categorie_sorties', $sortie->retreiveDateDebut(), $sortie->retreiveDateFin());

            $titre = $sortie->getNom();
            $event->setTitle('Sortie : ' . $titre);
            $event->setBgColor('#dd0000');

            $calendarEvent->addEvent($event);
        }
    }
}
