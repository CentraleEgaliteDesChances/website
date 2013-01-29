<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Représente un secteur de l'association Centrale Egalité des Chances.
 * En effet, l'association est structurée en différents secteurs,
 * chacun responsable d'un aspect de l'action et possédant un certain
 * nombre de membres.
 *
 * @see SecteurRepository
 * @see Membre
 *
 * @author Jean-Baptiste Bayle <jean-baptiste.bayle@student.ecp.fr>
 */
class Secteur
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

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
     * Set nom
     *
     * @param string $nom
     * @return Secteur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Add membres
     *
     * @param \CEC\MembreBundle\Entity\Membre $membres
     * @return Secteur
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
