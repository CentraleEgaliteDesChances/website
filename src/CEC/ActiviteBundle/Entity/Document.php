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
}
