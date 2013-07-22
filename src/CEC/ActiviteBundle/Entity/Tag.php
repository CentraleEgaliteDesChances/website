<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Un tag est un mot ou une courte expression permettant un classement efficace
 * des activités, tout en restant extrémement flexible.
 *
 * Le principe est identique aux tags des vidéos YouTube, par exemple : on associe un ou
 * plusieurs tags à une activité, et lors de la recherche rapide, on peut filtrer ces dernières
 * selon les tags déjà existants.
 *
 * Exemples de tags : niveau pour les étudiants (première, terminale, difficile...), des objectifs
 * pédagogiques visés (expression orale, écriture, raisonnement logique...), des notions abordées
 * (anglais, suites récurrentes, équations différentielles, actualités...).
 *
 * Un constructeur permet de créer rapidement un tag à l'aide de son contenu.
 * Il faut ensuite l'ajouter à une ou plusieurs activités.
 *
 * Un tag est entièrement défini par son contenu, qui doit donc être unique.
 *
 * IMPORTANT : Les tags sont désactivés dans la version 1.0 du site.
 *             Il s'agit d'une fonctionnalité à implémenter pour la version 2.0 ; pour cela,
 *             il suffit de créer un nouveau champ permettant de gérer les tags, par exemple
 *             à l'aide de http://ivaynberg.github.io/select2/#tags ou de http://aehlke.github.io/tag-it/examples.html?.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(
 *     fields = "contenu",
 *     message = "Un tag identique existe déjà."
 * )
 */
class Tag
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
     * Contenu du tag.
     * Ce doit être un mot ou une très courte expression, permettant de caractériser une activité.
     * Cette chaîne de caractère est requise, et ne peut excéder 50 caractères. Pour créer un tag
     * avec un contenu, utiliser le constructeur __construct($contenu).
     *
     * @var string
     *
     * @ORM\Column(name = "contenu", type = "string", length = 50)
     * @Assert\NotBlank(message = "Le contenu d'un tag ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 50,
     *     message = "Le tag ne peut excéder 50 caractères."
     * )
     */
    private $contenu;
    
    /**
     * Activités associées à ce tag.
     * Permet d'effectuer de manière rapide des recherches à base de mots-clefs.
     *
     * Il ne s'agit pas du côté propriétaire. Utilisez les méthodes d'Activite pour
     * ajouter une activité dans le groupe d'activités de ce tag.
     *
     * @var Activite
     *
     * @ORM\ManyToMany(targetEntity = "Activite", mappedBy = "tags")
     */
    private $activites;


    /**
     * Constructor
     *
     * @param string $contenu Contenu du tag.
     */
    public function __construct($contenu)
    {
        $this->activites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setContenu($contenu);
    }
    
    /**
     * Description d'un tag.
     * La méthode renvoit le contenu du tag.
     *
     * @return string Description du tag.
     */
    public function __toString()
    {
        return $this->getContenu();
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
     * Set contenu
     *
     * @param string $contenu
     * @return Tag
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }
    
    /**
     * Add activites
     *
     * @param \CEC\ActiviteBundle\Entity\Activite $activites
     * @return Tag
     */
    public function addActivite(\CEC\ActiviteBundle\Entity\Activite $activites)
    {
        $this->activites[] = $activites;
    
        return $this;
    }

    /**
     * Remove activites
     *
     * @param \CEC\ActiviteBundle\Entity\Activite $activites
     */
    public function removeActivite(\CEC\ActiviteBundle\Entity\Activite $activites)
    {
        $this->activites->removeElement($activites);
    }

    /**
     * Get activites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivites()
    {
        return $this->activites;
    }
}