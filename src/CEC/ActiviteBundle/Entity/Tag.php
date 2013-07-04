<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tag
 * (Jean-Baptiste Bayle — Mai 2013)
 *
 * Un tag est un mot ou une courte expression permettant un classement efficace
 * des activités, tout en restant extrémement flexible. Le principe est identique aux tags
 * des vidéos YouTube, par exemple : on associe un ou plusieurs tags à une activité,
 * et lors de la recherche rapide, on peut filtrer ces dernières selon les tags déjà existants.
 *
 * Exemples de tags : niveau pour les étudiants (première, terminale, difficile...), des objectifs
 * pédagogiques visés (expression orale, écriture, raisonnement logique...), des notions abordées
 * (anglais, suites récurrentes, équations différentielles, actualités...).
 *
 * Le constructeur permet de créer rapidement un tag à l'aide de son contenu.
 * Il faut ensuite l'ajouter à une ou plusieurs activités.
 *
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Tag
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
     * Contenu du tag : ce doit être un mot ou une très courte expression,
     * permettant de caractériser rapidement une activité.
     *
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $contenu;
    
    /**
     * Activités associées à ce tag.
     *
     * @var Activite
     *
     * @ORM\ManyToMany(targetEntity="Activite", mappedBy="tags")
     */
    private $activites;


    /**
     * Constructor
     *
     * @param string $contenu: contenu du tag
     */
    public function __construct($contenu)
    {
        $this->activites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setContenu($contenu);
    }
    
    /**
     * Description d'un tag : son contenu.
     *
     * @return string
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