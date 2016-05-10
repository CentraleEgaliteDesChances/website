<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Enfant
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Enfant
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="smallint")
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau_etude", type="string", length=255)
     */
    private $niveauEtude;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu_etude", type="string", length=255)
     */
    private $lieuEtude;

    /**

     * @ORM\ManyToOne(targetEntity="CEC\MembreBundle\Entity\DossierInscription")

     * @ORM\JoinColumn(nullable=false)

     */

    private $dossierInscription;

    /**
     * Enfant constructor.
     * @param $dossierInscription
     */
    public function __construct($dossierInscription)
    {
        $this->dossierInscription = $dossierInscription;
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
     * Set age
     *
     * @param integer $age
     * @return Enfant
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set niveauEtude
     *
     * @param string $niveauEtude
     * @return Enfant
     */
    public function setNiveauEtude($niveauEtude)
    {
        $this->niveauEtude = $niveauEtude;

        return $this;
    }

    /**
     * Get niveauEtude
     *
     * @return string 
     */
    public function getNiveauEtude()
    {
        return $this->niveauEtude;
    }

    /**
     * Set lieuEtude
     *
     * @param string $lieuEtude
     * @return Enfant
     */
    public function setLieuEtude($lieuEtude)
    {
        $this->lieuEtude = $lieuEtude;

        return $this;
    }

    /**
     * Get lieuEtude
     *
     * @return string 
     */
    public function getLieuEtude()
    {
        return $this->lieuEtude;
    }

    /**
     * @return mixed
     */
    public function getDossierInscription()
    {
        return $this->dossierInscription;
    }
    
    
}
