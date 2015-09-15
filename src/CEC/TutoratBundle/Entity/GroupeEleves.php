<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

/**
 * GroupeEleves
 */
class GroupeEleves
{
    /**
     * @var integer
     */
    private $id;

    /**
    * @var \CEC\MainBundle\Utility\AnneeScolaire
    */
    private $anneeScolaire;

    /**
    * @var \CEC\TutoratBundle\Entity\Groupe
    */
    private $groupe;

    /**
    * @var \Doctrine\Common\Collections\Collection
    */
    private $lyceen;

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
     * @return GroupeEleves
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
     * @return GroupeEleves
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
     * Set lyceen
     *
     * @param \CEC\MembreBundle\Entity\Eleve $lyceen
     * @return GroupeEleves
     */
    public function setLyceen(\CEC\MembreBundle\Entity\Eleve $lyceen = null)
    {
        $this->lyceen = $lyceen;
    
        return $this;
    }

    /**
     * Get lyceen
     *
     * @return \CEC\MembreBundle\Entity\Eleve 
     */
    public function getLyceen()
    {
        return $this->lyceen;
    }
}
