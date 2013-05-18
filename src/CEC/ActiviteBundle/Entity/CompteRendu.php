<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteRendu
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\ActiviteBundle\Entity\CompteRenduRepository")
 */
class CompteRendu
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
     * @ORM\Column(name="noteContenu", type="integer")
     */
    private $noteContenu;

    /**
     * @var integer
     *
     * @ORM\Column(name="noteInteractivite", type="integer")
     */
    private $noteInteractivite;

    /**
     * @var integer
     *
     * @ORM\Column(name="noteAtteinteObjectifs", type="integer")
     */
    private $noteAtteinteObjectifs;

    /**
     * @var integer
     *
     * @ORM\Column(name="dureeAdaptee", type="integer")
     */
    private $dureeAdaptee;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaires", type="text")
     */
    private $commentaires;

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
     * @ORM\ManyToOne(targetEntity="Activite", inversedBy="documents")
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
     * Set noteContenu
     *
     * @param integer $noteContenu
     * @return CompteRendu
     */
    public function setNoteContenu($noteContenu)
    {
        $this->noteContenu = $noteContenu;
    
        return $this;
    }

    /**
     * Get noteContenu
     *
     * @return integer 
     */
    public function getNoteContenu()
    {
        return $this->noteContenu;
    }

    /**
     * Set noteInteractivite
     *
     * @param integer $noteInteractivite
     * @return CompteRendu
     */
    public function setNoteInteractivite($noteInteractivite)
    {
        $this->noteInteractivite = $noteInteractivite;
    
        return $this;
    }

    /**
     * Get noteInteractivite
     *
     * @return integer 
     */
    public function getNoteInteractivite()
    {
        return $this->noteInteractivite;
    }

    /**
     * Set noteAtteinteObjectifs
     *
     * @param integer $noteAtteinteObjectifs
     * @return CompteRendu
     */
    public function setNoteAtteinteObjectifs($noteAtteinteObjectifs)
    {
        $this->noteAtteinteObjectifs = $noteAtteinteObjectifs;
    
        return $this;
    }

    /**
     * Get noteAtteinteObjectifs
     *
     * @return integer 
     */
    public function getNoteAtteinteObjectifs()
    {
        return $this->noteAtteinteObjectifs;
    }

    /**
     * Set dureeAdaptee
     *
     * @param integer $dureeAdaptee
     * @return CompteRendu
     */
    public function setDureeAdaptee($dureeAdaptee)
    {
        $this->dureeAdaptee = $dureeAdaptee;
    
        return $this;
    }

    /**
     * Get dureeAdaptee
     *
     * @return integer 
     */
    public function getDureeAdaptee()
    {
        return $this->dureeAdaptee;
    }

    /**
     * Set commentaires
     *
     * @param string $commentaires
     * @return CompteRendu
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    
        return $this;
    }

    /**
     * Get commentaires
     *
     * @return string 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return CompteRendu
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
     * @return CompteRendu
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
     * @return CompteRendu
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
     * @return CompteRendu
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