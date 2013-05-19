<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Activite
 * (Jean-Baptiste Bayle — Mai 2013)
 *
 * Une activité représente un contenu pédagogique clef-en-main que les tuteurs
 * utilisent pour réaliser une séance de tutorat. Une séance peut se composer de plusieurs
 * activités, sélectionnées préalablement à la séance par le VP Lycée ou un tuteur du groupe.
 * En séance, les tuteurs peuvent télécharger et consulter le document associé à la séance.
 *
 * A la suite de la séance, on demande de remplir un compte-rendu associé à l'activité,
 * et qui permettra, si nécessaire, des rectifications ou une aide au choix de l'activité
 * pour les futurs tuteurs. On peut par exemple masquer les activités déjà utilisées pour un groupe.
 *
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Activite
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
     * Titre de l'activité.
     *
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $titre;

    /**
     * Description rapide de l'activité.
     * La description est affichée lorsque l'on choisit une activité pour une séance,
     * et permet de cibler rapidement son contenu.
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * Durée approximative de l'activité, permettant au tuteur choisissant
     * une activité d'organiser sa séance. Peut indiquer d'autres indications courtes,
     * comme "Indéfinie", "Environ ...", "Adaptable"...
     *
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $duree;

    /**
     * Type d'activité, à choisir parmi les valeurs suivantes :
     * Activité Culturelle, Activité Scientifique, Expérience Scientifique et Autre.
     * Cela permet de filtrer très rapidement les activités lors de leur recherche par un tuteur.
     * 
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\Choice(
     *     choices = {"Activité Culturelle", "Activité Scientifique", "Expérience Scientifique", "Autre"},
     *     message = "Le type doit être Activité Culturelle, Activité Scientifique, Expérience Scientifique ou Autre."
     * )
     */
    private $type;

    /**
     * Date de création.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $dateCreation;

    /**
     * Date de dernière modification.
     * 
     * @var \DateTime
     *
     * @ORM\Column(name="dateModification", type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $dateModification;
    
    /**
     * Séances associées à cette activité.
     * Une séance est ajotuée lorsqu'un tuteur sélectionne l'activité lors d'une séance.
     * 
     * @var CEC\TutoratBundle\Entity\Seance
     *
     * @ORM\ManyToMany(targetEntity="CEC\TutoratBundle\Entity\Seance", mappedBy="activites")
     */
    private $seances;
    
    /**
     * Tags associés à l'activité.
     * Le système de classement par tag permet d'accélérer la recherche d'activité
     * tout en permettant une très grande flexibilité.
     * Les tags peuvent représenter, par exemple, le niveau conseillé (premières, terminales), 
     * les objectifs pédagogiques ou les notions recouvertes, le niveau de ludicité de l'activité...
     * 
     * @var Tag
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="activites")
     */
    private $tags;
    
    /**
     * Les diverses versions du document associé à l'activité.
     * Cela permet de garder un historique des versiosn de l'activité, d'ajouter des corrections
     * en fonction des comptes-rendus effectués en créant une nouvelle version, etc.
     * 
     * @var Document
     *
     * @ORM\OneToMany(targetEntity="Document", mappedBy="activite")
     */
    private $versions;
    
    /**
     * Compte-rendus rédigées concernant cette activité.
     * Les comptes-rendus sont rédigés à la suite d'une séance de tutorat par le VP Lycée
     * ou un tuteur et permette aux secteurs Activités d'améliorer les activités en uploadant
     * de nouvelles versions avec corrections.
     * Ils permettent par ailleurs d'aider au choix d'activité avant la séance.
     *
     * @var CompteRendu
     *
     * @ORM\OneToMany(targetEntity="CompteRendu", mappedBy="activite")
     */
    private $compteRendus;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->compteRendus = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Retourne la dernière version du document.
     * Renvoit false s'il n'y a aucune version disponible.
     *
     * @return mixed
     */
    public function getDocument()
    {
        return $this->getVersions()->last();
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
     * Set titre
     *
     * @param string $titre
     * @return Activite
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Activite
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set duree
     *
     * @param string $duree
     * @return Activite
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
    
        return $this;
    }

    /**
     * Get duree
     *
     * @return string 
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Activite
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Activite
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
     * @return Activite
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
     * Add seances
     *
     * @param \CEC\TutoratBundle\Entity\Seance $seances
     * @return Activite
     */
    public function addSeance(\CEC\TutoratBundle\Entity\Seance $seances)
    {
        $this->seances[] = $seances;
    
        return $this;
    }

    /**
     * Remove seances
     *
     * @param \CEC\TutoratBundle\Entity\Seance $seances
     */
    public function removeSeance(\CEC\TutoratBundle\Entity\Seance $seances)
    {
        $this->seances->removeElement($seances);
    }

    /**
     * Get seances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeances()
    {
        return $this->seances;
    }

    /**
     * Add tags
     *
     * @param \CEC\ActiviteBundle\Entity\Tag $tags
     * @return Activite
     */
    public function addTag(\CEC\ActiviteBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \CEC\ActiviteBundle\Entity\Tag $tags
     */
    public function removeTag(\CEC\ActiviteBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add versions
     *
     * @param \CEC\ActiviteBundle\Entity\Document $versions
     * @return Activite
     */
    public function addVersion(\CEC\ActiviteBundle\Entity\Document $versions)
    {
        $this->versions[] = $versions;
    
        return $this;
    }

    /**
     * Remove versions
     *
     * @param \CEC\ActiviteBundle\Entity\Document $versions
     */
    public function removeVersion(\CEC\ActiviteBundle\Entity\Document $versions)
    {
        $this->versions->removeElement($versions);
    }

    /**
     * Get versions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * Add compteRendus
     *
     * @param \CEC\ActiviteBundle\Entity\CompteRendu $compteRendus
     * @return Activite
     */
    public function addCompteRendu(\CEC\ActiviteBundle\Entity\CompteRendu $compteRendus)
    {
        $this->compteRendus[] = $compteRendus;
    
        return $this;
    }

    /**
     * Remove compteRendus
     *
     * @param \CEC\ActiviteBundle\Entity\CompteRendu $compteRendus
     */
    public function removeCompteRendu(\CEC\ActiviteBundle\Entity\CompteRendu $compteRendus)
    {
        $this->compteRendus->removeElement($compteRendus);
    }

    /**
     * Get compteRendus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompteRendus()
    {
        return $this->compteRendus;
    }
}