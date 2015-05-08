<?php

namespace CEC\SecteurProjetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

/**
 * ProjetEleve
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\SecteurProjetsBundle\Entity\ProjetEleveRepository")
 */
class ProjetEleve
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
    * @var AnneeScolaire $anneeScolaire 
    *
    * @ORM\Column(name="anneeScolaire")
    */
    private $anneeScolaire;

    /**
    * @var \CEC\SecteurProjetsBundle\Entity\Projet
    *
    * @ORM\ManyToOne(targetEntity="CEC\SecteurProjetsBundle\Entity\Projet", inversedBy="inscritsParAnnee")
    */
    private $projet;

    /**
    * @var \CEC\MembreBundle\Entity\Eleve
    *
    * @ORM\ManyToOne(targetEntity="\CEC\MembreBundle\Entity\Eleve", inversedBy="projetsParAnnee")
    */
    private $lyceen;


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
     * @param string $anneeScolaire
     * @return ProjetEleve
     */
    public function setAnneeScolaire($anneeScolaire)
    {
        $this->anneeScolaire = $anneeScolaire;
    
        return $this;
    }

    /**
     * Get anneeScolaire
     *
     * @return string 
     */
    public function getAnneeScolaire()
    {
        return $this->anneeScolaire;
    }

    /**
     * Set projet
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Projet $projet
     * @return ProjetEleve
     */
    public function setProjet(\CEC\SecteurProjetsBundle\Entity\Projet $projet = null)
    {
        $this->projet = $projet;
    
        return $this;
    }

    /**
     * Get projet
     *
     * @return \CEC\SecteurProjetsBundle\Entity\Projet 
     */
    public function getProjet()
    {
        return $this->projet;
    }

    /**
     * Set lyceen
     *
     * @param \CEC\MembreBundle\Entity\Eleve $lyceen
     * @return ProjetEleve
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