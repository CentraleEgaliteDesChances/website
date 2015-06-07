<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Représente un secteur de l'association.
 * 
 * Centrale Égalité des Chances est organisé en secteur, dont chacun s'attache à une action de l'association.
 * Cette entité permet donc de répertorier les secteurs et d'associer les membres aux secteurs qu'ils fréquentent.
 * Le site interne met alors à leur disposition des pages et raccourcis spécifiques utiles à leur travail.
 *
 * Un secteur est identifié par son nom, qui doit être unique.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.1
 * 
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(
 *     fields = "nom",
 *     message = "Un secteur possédant ce nom existe déjà."
 * )
 */
class Secteur
{
    /**
     * @var integer
     *
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * Nom du secteur.
     * Le nom permet d'identifier l'action du secteur ; il contient par habitude le préfixe
     * "Secteur", comme par exemple "Secteur Sorties" ou "Secteur Activités Scientifiques".
     * Le nom est requis, unique, et ne peut excéder 100 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "nom", type = "string", length = 100)
     * @Assert\NotBlank(message = "Le nom du secteur ne peut être vide.")
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = "Le nom du secteur ne peut excéder 100 caractères."
     * )
     */
    private $nom;

    /**
     * Date de création.
     *
     * @var \DateTime
     *
     * @ORM\Column(name = "dateCreation", type = "datetime")
     * @Gedmo\Timestampable(on = "create")
     * @Assert\DateTime()
     */
    private $dateCreation;

    /**
     * Date de dernière modification.
     * 
     * @var \DateTime
     *
     * @ORM\Column(name = "dateModification", type = "datetime")
     * @Gedmo\Timestampable(on = "update")
     * @Assert\DateTime()
     */
    private $dateModification;

    /**
     * Membres du secteur.
     * Ce champ liste les membres participant aux activités du secteur.
     *
     * Il ne s'agit pas du côté propriétaire. Utilisez les méthodes de Membre pour
     * ajouter un membre à un secteur.
     * 
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity = "Membre", mappedBy = "secteurs" )
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
     * Retourne la description d'un secteur.
     * La description est le nom du secteur seulement.
     *
     * @return string Description du secteur.
     */
    public function __toString()
    {
        
        return $this->getNom();
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Secteur
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
     * @return Secteur
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