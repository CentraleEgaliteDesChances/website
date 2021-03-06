<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

/**
 * GroupeTuteurs
 */
class GroupeTuteurs
{
    /**
     * @var integer
     */
    private $id;

    /**
    * @var AnneeScolaire
    */
    private $anneeScolaire;

    /**
    * @var \CEC\TutoratBundle\Entity\Groupe
    */
    private $groupe;

    /**
    * @var \Doctrine\Common\Collections\Collection
    */
    private $tuteur;

    /**
    * constructor
    */
    public function __construct()
    {
        $this->anneescolaire = AnneeScolaire::withDate();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set anneeScolaire
     *
     * @param anneescolaire $anneeScolaire
     * @return GroupeTuteurs
     */
    public function setAnneeScolaire($anneeScolaire)
    {
        $this->anneeScolaire = $anneeScolaire;
    
        return $this;
    }

    /**
     * Get anneeScolaire
     *
     * @return anneescolaire 
     */
    public function getAnneeScolaire()
    {
        return $this->anneeScolaire;
    }

    /**
     * Set groupe
     *
     * @param \CEC\TutoratBundle\Entity\Groupe $groupe
     * @return GroupeTuteurs
     */
    public function setGroupe(\CEC\TutoratBundle\Entity\Groupe $groupe = null)
    {
        $this->groupe = $groupe;
    
        return $this;
    }

    /**
     * Get groupe
     *
     * @return \CEC\TutoratBundle\Entity\Groupe 
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set tuteur
     *
     * @param \CEC\MembreBundle\Entity\Membre $tuteur
     * @return GroupeTuteurs
     */
    public function setTuteur(\CEC\MembreBundle\Entity\Membre $tuteur = null)
    {
        $this->tuteur = $tuteur;
    
        return $this;
    }

    /**
     * Get tuteur
     *
     * @return \CEC\MembreBundle\Entity\Membre 
     */
    public function getTuteur()
    {
        return $this->tuteur;
    }
}
