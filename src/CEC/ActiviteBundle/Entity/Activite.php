<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Représente un contenu prédagogique que les tuteurs peuvent utiliser clef-en-main,
 * en combinaison avec d'autres activités, pour réaliser une séance de tutorat.
 *
 * Une séance peut se composer de plusieurs activités, sélectionnées et attribuées
 * préalablement à la séance par le VP Lycée ou un tuteur du groupe. En séance, les
 * tuteurs peuvent télécharger et consulter le document associé à la séance.
 *
 * A la suite de la séance, on demande de remplir un compte-rendu (CompteRendu) associé à l'activité,
 * et qui permettra, si nécessaire, des rectifications ou une aide au choix de l'activité
 * pour les futurs tuteurs. On peut par exemple masquer les activités déjà utilisées pour un groupe.
 *
 * Une activité est identifiée par son titre, qui doit donc être unique.
 *
 * Lorsque l'on supprime une activité, ses versions (classe Document) sont aussi supprimées,
 * et les fichiers sont retirés du serveur définitivement.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CEC\ActiviteBundle\Entity\ActiviteRepository")
 * @UniqueEntity(
 *     fields = "titre",
 *     message = "Une activité possédant le même titre existe déjà."
 * )
 */
class Activite
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
     * Titre de l'activité.
     * Le titre de l'activité doit être une chaîne de caractère unique et non vide,
     * et celle-ci ne doit pas excéder 100 caractères. Elle permet l'identification de
     * l'activité lors du processus de recherche d'une activité.
     *
     * @var string
     *
     * @ORM\Column(name = "titre", type = "string", length = 100)
     * @Assert\NotBlank(message = "Le titre de l'activité ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "Le titre de l'activité ne peut excéder 100 caractères."
     * )
     */
    private $titre;

    /**
     * Brève description de l'activité.
     * La description est affichée lorsque l'on choisit une activité pour une séance,
     * et permet de cibler rapidement son contenu. Il s'agit d'une chaîne de caractère
     * permettant de décrire en moins de 800 caractères le contenu de l'activité.
     * La description est requise pour chaque activité.
     *
     * @var string
     *
     * @ORM\Column(name = "description", type = "text")
     * @Assert\NotBlank(message = "La description de l'activité ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 800,
     *     message = "La description de l'activité ne peut excéder 800 caractères."
     * )
     */
    private $description;

    /**
     * Durée approximative de l'activité.
     * Il s'agit d'une chaîne de caractère de moins de 255 caractères, permettant
     * aux tuteurs choisissant l'activité d'organiser sa séance. On indique la durée
     * à titre indicatif (ex : "1h30") ; on peut aussi utiliser de courtes indications,
     * comme "Indéfinie", "Environ ...", "Adaptable", "Variable", etc.
     *
     * @var string
     *
     * @ORM\Column(name = "duree", type = "string", length = 255)
     * @Assert\NotBlank(message = "Merci d'indiquer, à titre indicatif, une durée approximative pour l'activité")
     * @Assert\MaxLength(
     *     limit = 255,
     *     message = "L'indication de durée ne peut excéder 255 caractères."
     * )
     */
    private $duree;

    /**
     * Type d'activité.
     * À choisir parmi les valeurs suivantes :
     * Activité Culturelle, Activité Scientifique, Expérience Scientifique et Autre.
     * Cela permet de filtrer très rapidement les activités lors de leur recherche par un tuteur,
     * afin que celles-ci soient compatibles avec la séances ou de cibler la recherche.
     *
     * @var string
     *
     * @ORM\Column(name = "type", type = "string", length = 255)
     * @Assert\Choice(
     *     choices = {"Activité Culturelle", "Activité Scientifique", "Expérience Scientifique", "Autre"},
     *     message = "Le type d'activité choisi n'est pas valide."
     * )
     */
    private $type;

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
     * Tags associés à l'activité.
     * Le système de classement par tag (classe Tag) permet d'accélérer la recherche
     * d'activité tout en permettant une très grande flexibilité.
     * Les tags peuvent représenter, par exemple, le niveau conseillé (premières, terminales),
     * les objectifs pédagogiques ou les notions abordées, ainsi que le niveau de ludicité de l'activité
     * ou tout autre attribut permettant d'aider les tuteurs lors de la recherche d'activité.
     *
     * IMPORTANT : Cette fonctionnalité n'est pas implémentée dans la version 1.0 du site.
     *             Merci de se reporter à la description de la classe Tag pour plus d'infos.
     *
     * @var Tag
     *
     * @ORM\ManyToMany(targetEntity = "Tag", inversedBy = "activites")
     */
    private $tags;

    /**
     * Les diverses versions du document associé à l'activité.
     * Cette fonction permet de garder un historique des versions de l'activité, à la suite
     * des corrections apportées en fonction des comptes-rendus effectués et qui ont abouties
     * à la création de nouvelles versions de l'activité.
     * Une activité doit au minimum posséder une version.
     * Pour obtenir la dernière version, utiliser la méthode getDocument().
     *
     * Il ne s'agit pas du côté propriétaire. Utilisez les méthodes de Document pour
     * ajouter une activité à un document.
     *
     * @var Document
     *
     * @ORM\OneToMany(targetEntity = "Document", mappedBy = "activite", cascade = {"remove"} )
     */
    private $versions;

    /**
     * Compte-rendus rédigées concernant cette activité.
     * Les comptes-rendus (classe CompteRendu) sont rédigés à la suite d'une séance de tutorat
     * par le VP Lycée ou un tuteur afin de permette aux secteurs Activités de corriger les activités
     * en téléchargeant de nouvelles versions avec corrections.
     * Ils permettent par ailleurs d'aider au choix d'activité avant la séance grâce aux notes
     * qui sont décernées, et permettent d'établir des statistiques fiables sur l'activité de tutorat.
     *
     * Il ne s'agit pas du côté propriétaire. Utilisez les méthodes de CompteRendu
     * pour ajouter une activité relativement à un compte-rendu.
     *
     * @var CompteRendu
     *
     * @ORM\OneToMany(targetEntity = "CompteRendu", mappedBy = "activite", cascade = {"remove"} )
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
     * La méthode renvoit "false" s'il n'y a aucune version disponible, ce qui ne doit
     * normalement pas arriver puisque le champ "versions" est contrainte à être non-vide.
     *
     * @return mixed Dernière version du document associé à l'activité.
     */
    public function getDocument()
    {
        return $this->getVersions()->last();
    }

    /**
     * Description de l'activité.
     * La méthode renvoit le titre de l'activité.
     *
     * @return string Description de l'activité.
     */
    public function __toString()
    {
        return $this->getTitre();
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
