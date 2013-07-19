<?php

namespace CEC\ActiviteBundle\Entity;

use CEC\ActiviteBundle\Entity\Activite;
use CEC\ActiviteBundle\Entity\Document;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Représente une nouvelle activité, et donc une instance de la classe Activite associée
 * à une instance de la classe Document (première version de l'activité, téléchargée en même
 * temps que l'on crée l'activité).
 *
 * Cette classe n'est pas persistée, mais est utilisée pour la création de nouvelles activité
 * dans le formulaire NouvelleActiviteType.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 */
class NouvelleActivite
{
    /**
     * Activité.
     * 
     * @var CEC\ActiviteBundle\Entity\Activite
     * @Assert\Valid
     */
    private $activite;
    
    /**
     * Document — Première version de l'activité.
     * 
     * @var CEC\ActiviteBundle\Entity\Document
     * @Assert\Valid
     */
    private $document;
    
    
    /**
     * Constructeur — crée les instances d'Activite et de Document.
     */
    public function __construct() {
        $this->setActivite(new Activite());
        $this->setDocument(new Document());
    }
    
    public function getActivite() {
        return $this->activite;
    }
    
    public function getDocument() {
        return $this->document;
    }
    
    public function setActivite(Activite $activite) {
        $this->activite = $activite;
        return $this;
    }
    
    public function setDocument(Document $document) {
        $this->document = $document;
        return $this;
    }
}