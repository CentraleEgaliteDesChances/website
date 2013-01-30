<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promotion
 */
class Promotion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $annee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $membres;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set annee
     *
     * @param integer $annee
     * @return Promotion
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
    
        return $this;
    }

    /**
     * Get annee
     *
     * @return integer 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Add membres
     *
     * @param \CEC\MembreBundle\Entity\Membre $membres
     * @return Promotion
     */
    public function addMembre(\CEC\MembreBundle\Entity\Membre $membres)
    {
        $this->membres[] = $membres;
    
        return $this;
    }

    /**
     * Remove membres
     *
     * @param \CEC\MembreBundle\Entity\Membre $membres
     */
    public function removeMembre(\CEC\MembreBundle\Entity\Membre $membres)
    {
        $this->membres->removeElement($membres);
    }

    /**
     * Get membres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembres()
    {
        return $this->membres;
    }
}
