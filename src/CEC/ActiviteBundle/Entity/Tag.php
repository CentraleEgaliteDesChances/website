<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
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
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;
    
    /**
     * @var Activite
     *
     * @ORM\ManyToMany(targetEntity="Activite", mappedBy="tags")
     */
    private $activites;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activites = new \Doctrine\Common\Collections\ArrayCollection();
    }

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