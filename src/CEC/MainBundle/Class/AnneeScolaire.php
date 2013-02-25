<?php

namespace CEC\MainBundle\Class;

/**
 * Représente une année scolaire
 */
class AnneeScolaire
{
    /**
     * @var integer
     */
    private $_anneeScolaire;
    
    /**
     * Constructor
     *
     * @param \DateTime $date: date utilisée pour construire l'objet
     */
    public function __construct(\DateTime $date = null)
    {
        if (!$date) $date = new \DateTime();
        
        if ($date->format('n') >= 8) {
            $this->setAnneeScolaire($date->format('Y'));
        } else {
            $this->setAnneeScolaire($date->format('Y') - 1);
        }
    }
    
    /**
     * Get anneeScolaire
     *
     * @return integer
     */
    public function getAnneeScolaire()
    {
        return $this->_anneeScolaire;
    }
    
    /**
     * Set anneeScolaire
     *
     * @param integer $anneeScolaire: annee scolaire
     * @return AnneeScolaire
     */
    public function setAnneeScolaire($anneeScolaire)
    {
        if (is_integer($anneeScolaire)) $this->_anneeScolaire = $anneeScolaire;
        return $this;
    }
}
