<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Document
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
     * @var string
     *
     * @ORM\Column(name="fichierPDF", type="string", length=255)
     */
    private $fichierPDF;

    /**
     * @var string
     *
     * @ORM\Column(name="fichierWord", type="string", length=255)
     */
    private $fichierWord;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModification", type="datetime")
     */
    private $dateModification;
    
    /**
     * @var Activite
     *
     * @ORM\ManyToOne(targetEntity="Activite", inversedBy="versions")
     */
    private $activite;
    
    /**
     * @var CEC\MembreBundle\Entity\Activite
     *
     * @ORM\ManyToOne(targetEntity="CEC\MembreBundle\Entity\Membre", inversedBy="documents")
     */
    private $auteur;


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
     * Set fichierPDF
     *
     * @param string $fichierPDF
     * @return Document
     */
    public function setFichierPDF($fichierPDF)
    {
        $this->fichierPDF = $fichierPDF;
    
        return $this;
    }

    /**
     * Get fichierPDF
     *
     * @return string 
     */
    public function getFichierPDF()
    {
        return $this->fichierPDF;
    }

    /**
     * Set fichierWord
     *
     * @param string $fichierWord
     * @return Document
     */
    public function setFichierWord($fichierWord)
    {
        $this->fichierWord = $fichierWord;
    
        return $this;
    }

    /**
     * Get fichierWord
     *
     * @return string 
     */
    public function getFichierWord()
    {
        return $this->fichierWord;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Document
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Document
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    
        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set activite
     *
     * @param \CEC\ActiviteBundle\Entity\Activite $activite
     * @return Document
     */
    public function setActivite(\CEC\ActiviteBundle\Entity\Activite $activite = null)
    {
        $this->activite = $activite;
    
        return $this;
    }

    /**
     * Get activite
     *
     * @return \CEC\ActiviteBundle\Entity\Activite 
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set auteur
     *
     * @param \CEC\MembreBundle\Entity\Activite $auteur
     * @return Document
     */
    public function setAuteur(\CEC\MembreBundle\Entity\Activite $auteur = null)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return \CEC\MembreBundle\Entity\Activite 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }
}