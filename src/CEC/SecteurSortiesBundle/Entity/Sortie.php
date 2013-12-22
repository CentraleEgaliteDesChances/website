<?php

namespace CEC\SecteurSortiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Représente une sortie prévue et organisée par le secteur sortie.
 *
 * Cette entité permet de lister toutes les sorties qui ont étés faites et qui sont à venir.
 *
 * Une sortie est identifié par son nom qui doit être unique.
 *
 * @author Corentin Bertrand
 * @version 1.0
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CEC\SecteurSortiesBundle\Entity\SortieRepository")
 * @UniqueEntity(
 *     fields = "nom",
 *     message = "Une sortie possédant ce nom existe déjà."
 * )
 */
class Sortie
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
     * Nom de la sortie.
     * Le nom est requis, unique, et ne peut excéder 100 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "nom", type = "string", length = 100)
     * @Assert\NotBlank(message = "Le nom de la sortie ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "Le nom de la sortie ne peut excéder 100 caractères."
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
     * Adresse de rendez-vous de la sortie
     *
     * @var string
     *
     * @ORM\Column(name = "adresse", type = "string", length = 100)
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "L'adresse ne peut excéder 100 caractères."
     * )
     */
    private $adresse;

    /**
     * Date prévue de la sortie
     *
     * @var \Date
     *
     * @ORM\Column(name = "dateSortie", type = "date")
     * @Assert\Date()
     */
    private $dateSortie;

    /**
     * Heure de rendez-vous
     *
     * @var \Time
     *
     * @ORM\Column(name = "heureDebut", type = "time")
     * @Assert\Time()
     */
    private $heureDebut;

    /**
     * Heure approximative de fin
     *
     * @var \Time
     *
     * @ORM\Column(name = "heureFin", type = "time")
     * @Assert\Time()
     */
    private $heureFin;

    /**
     * Description de la sortie
     *
     * @var string
     *
     * @ORM\Column(name = "description", type = "string", length = 255)
     * @Assert\MaxLength(
     *     limit = 255,
     *     message = "La description ne peut excéder 255 caractères."
     * )
     */
    private $description;


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
     * @return Sortie
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
     * @return Sortie
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
     * @return Sortie
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
     * Set adresse
     *
     * @param string $adresse
     * @return Sortie
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
     * @return Sortie
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return \DateTime
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
    }

    /**
     * Set heureDebut
     *
     * @param \DateTime $heureDebut
     * @return Sortie
     */
    public function setHeureDebut($heureDebut)
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    /**
     * Get heureDebut
     *
     * @return \DateTime
     */
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }

    /**
     * Set heureFin
     *
     * @param \DateTime $heureFin
     * @return Sortie
     */
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    /**
     * Get heureFin
     *
     * @return \DateTime
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Sortie
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
}
