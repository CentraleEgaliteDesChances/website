<?php

namespace CEC\CalendrierBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use CEC\TutoratBundle\Entity\Seance;

class SeancesPlanningEventListener
{
    private $entityManager;
    private $router;
    
    private $couleurGris = 'gray';    // Couleur pour les séances indisponibles
    private $couleurs = array(        // Liste des couleurs disponibles pour les séances
        '#980000',
        '#0f0098',
        '#0f9800',
        '#6e0098',
        '#005398',
        '#983200',
        '#988700',
        '#009890',
        '#dc007d',
        '#dc4400',
        '#23dc00',
        '#00b8dc',
        '#dc0000',
        '#003adc',
        '#7d00dc',
        '#dc0000'
    );
    
    private $groupesCouleurs = array(); // Association des groupes avec les couleurs
    

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
        $seances = $this->entityManager
                        ->getRepository('CECTutoratBundle:Seance')
                        ->findAllBetweenDates($dateDebut, $dateFin);

        foreach($seances as $seance)
        {
            // On crée l'event et on attribue l'URL
            $event = new EventEntity('Séance de tutorat', $seance->retreiveDateDebut(), $seance->retreiveDateFin());
            $event->setUrl($this->router->generate('seance', array('seance' => $seance->getId())));
            
            if ($seance->getGroupe())
            {
                $titre = $seance->getGroupe()->getDescription();
                $event->setTitle($titre);
                $event->setBgColor($this->couleurPourGroupe($seance->getGroupe()->getId()));
            } else {
                $event->setBgColor($this->couleurGris);
            }

            $calendarEvent->addEvent($event);
        }
    }
    
    /**
     * Renvoit une couleur pour un groupe donné.
     *
     * @param integer $groupe: id du groupe
     * @return string
     */
    private function couleurPourGroupe($groupe)
    {
        if (!array_key_exists($groupe, $this->groupesCouleurs))
        {
            $nombreCouleursUtilisees = count(array_keys($this->groupesCouleurs));
            $nouvelleCouleur = $this->couleurs[ $nombreCouleursUtilisees % count($this->couleurs) ];
            $this->groupesCouleurs[$groupe] = $nouvelleCouleur;
        }
        return $this->groupesCouleurs[$groupe];
    }    
}
