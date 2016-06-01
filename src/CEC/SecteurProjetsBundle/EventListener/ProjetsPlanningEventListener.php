<?php

namespace CEC\SecteurProjetsBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use CEC\SecteurSortiesBundle\Entity\Sortie;

class ProjetsPlanningEventListener
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
        $projets = $this->entityManager
                        ->getRepository('CECSecteurProjetsBundle:Projet')
                        ->findAllBetweenDates($dateDebut, $dateFin);

        foreach($projets as $projet)
        {
            // On crée l'event
            $event = new EventEntity('Projet','categorie_projets', $projet->retreiveDateDebut(), $projet->retreiveDateFin());

            $titre = $projet->getNom();
            $event->setTitle('Projet : ' . $titre);
            $event->setBgColor('#00dd00');
            $event->setUrl($this->router->generate('description_projet', array('slug' => $projet->getSlug())));

            $calendarEvent->addEvent($event);
        }
    }
}
