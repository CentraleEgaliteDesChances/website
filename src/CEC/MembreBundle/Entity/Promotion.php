<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Représente une promotion de l'école Centrale Paris, et donc
 * un groupe de Membres de la même année.
 *
 * L'année associée est calculée de la manière suivante : année
 * d'intégration + 3, et correspond à l'année du diplôme pour un 
 * cursus classique.
 *
 * @see PromotionRepository
 * @see Membre
 *
 * @author Jean-Baptiste Bayle <jean-baptiste.bayle@student.ecp.fr>
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
     * @var \CEC\MembreBundle\Entity\Membre
     */
    private $membre;


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
     * Set membre
     *
     * @param \CEC\MembreBundle\Entity\Membre $membre
     * @return Promotion
     */
    public function setMembre(\CEC\MembreBundle\Entity\Membre $membre = null)
    {
        $this->membre = $membre;
    
        return $this;
    }

    /**
     * Get membre
     *
     * @return \CEC\MembreBundle\Entity\Membre 
     */
    public function getMembre()
    {
        return $this->membre;
    }
}
