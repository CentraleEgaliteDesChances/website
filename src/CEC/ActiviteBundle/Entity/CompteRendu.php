<?php

namespace CEC\ActiviteBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteRendu
 * (Jean-Baptiste Bayle — Mai 2013)
 *
 * Un compte-rendu représente un feedback sur une activité effectué par un VP Lycée ou un tuteur
 * à la suite d'une séance de tutorat pendant laquelle cette activité à été proposée aux
 * lycéens. Il est ensuite consulté par les membres des secteurs Activités qui cherchent
 * à améliorer ou corriger les défauts des activités en soumettant une nouvelle version.
 *
 * Il faut rendre la rédaction d'un compte-rendu très facile : c'est pourquoi 3 critères simples 
 * ont été précisément définis, plus une appréciation de la durée nécessaire pour réaliser 
 * l'activité ainsi qu'un champ de commentaires libres.
 *
 * Les notes sont définies sur 5, 5 étant la note maximale (Très bon avis).
 * La méthode getNoteGlobale() permet d'obtenir une moyenne des notes associées au compte-rendu.
 *
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
     * Note de contenu : le contenu du sujet est-il interessant
     * et possède-t-il un contenu pdégogique pertinent ?
     *
     * @var integer
     *
     * @ORM\Column(name="noteContenu", type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *     min = 1,
     *     max = 5
     * )
     */
    private $noteContenu;

    /**
     * Note d'interactivité : le sujet est-il suffisamment interactif et
     * ludique ? A-t-il été globalement bien suivi par les lycéens ?
     *
     * @var integer
     *
     * @ORM\Column(name="noteInteractivite", type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *     min = 1,
     *     max = 5
     * )
     */
    private $noteInteractivite;

    /**
     * Note d'atteinte d'objectifs : les objectifs pédagogiques annocés
     * de l'activité ont-ils été atteints à la fin de la séance ?
     *
     * @var integer
     *
     * @ORM\Column(name="noteAtteinteObjectifs", type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *     min = 1,
     *     max = 5
     * )
     */
    private $noteAtteinteObjectifs;

    /**
     * La durée de l'activité est-elle adaptée et en accord
     * avec celle qui est annoncée sur la description de l'annonce ?
     * Les valeurs acceptées sont définies par les constantes de classes suivantes.
     * 
     * @var integer
     *
     * @ORM\Column(name="dureeAdaptee", type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *     min = -1,
     *     max = 1
     * )
     */
    const CompteRenduDureeAnnonceeTropCourte = -1;
    const CompteRenduDureeAdaptee            = 0;
    const CompteRenduDureeAnnonceeTropLongue = 1;
    private $dureeAdaptee;

    /**
     * Laisse un espace au tuteur pour communiquer ses commentaires
     * libre au secteur Activités à propos de l'activité.
     *
     * @var string
     *
     * @ORM\Column(name="commentaires", type="text")
     */
    private $commentaires;

    /**
     * Date de création.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     * @Assert\NotBlank()
     */
    private $dateCreation;

    /**
     * Date de dernière modification.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="dateModification", type="datetime")
     * @Assert\NotBlank()
     */
    private $dateModification;
    
    /**
     * Activité associée au compte-rendu.
     *
     * @var Activite
     *
     * @ORM\ManyToOne(targetEntity="Activite", inversedBy="documents")
     * @Assert\NotBlank()
     */
    private $activite;
    
    /**
     * Membre auteur du compte-rendu. Il est enregistré lors de l'ajout du compte-rendu
     * et permet de garder une trace de l'activité du membre.
     *
     * @var CEC\MembreBundle\Entity\Membre
     *
     * @ORM\ManyToOne(targetEntity="CEC\MembreBundle\Entity\Membre", inversedBy="documents")
     * @Assert\NotBlank()
     */
    private $auteur;
    
    
    /**
     * Retourne la moyenne des notes décernées dans le compte-rendu, sur 5.
     * Retourne false si une des notes n'est pas définie.
     *
     * @return integer
     */
    public function getNoteGlobale()
    {
        if ($this->getNoteContenu() and 
            $this->getNoteInteractivite() and 
            $this->getNoteAtteinteObjectifs())
        {
            return ($this->getNoteContenu() + $this->getNoteInteractivite() + $this->getNoteAtteinteObjectifs()) / 3;
        } else {
            return false;
        }
    }

    
    
    //
    // Doctrine-generated accessors
    //

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