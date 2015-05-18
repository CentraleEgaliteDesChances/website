<?php

namespace CEC\SecteurSortiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SortieEleve
 * 
 * Classe repéertoriant les inscriptiosn des élèves aux sorties en notant en mémoire leur position sur liste d'attente
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SortieEleve
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
     * @ORM\Column(name="listeAttente", type="integer")
     */
    private $listeAttente = 0;

    /**
    *
    * @var boolean
    *
    * @ORM\Column(name="presence", type="boolean")
    */
    private $presence=true;

    /**
    *
    * @ORM\ManyToOne(targetEntity="\CEC\MembreBundle\Entity\Eleve", inversedBy="sorties")
    */
    private $lyceen;

    /**
    *
    * @ORM\ManyToOne(targetEntity="\CEC\SecteurSortiesBundle\Entity\Sortie", inversedBy="lyceens")
    */
    private $sortie;


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
     * Set listeAttente
     *
     * @param integer $listeAttente
     * @return SortieEleve
     */
    public function setListeAttente($listeAttente)
    {
        $this->listeAttente = $listeAttente;
    
        return $this;
    }

    /**
     * Get listeAttente
     *
     * @return integer 
     */
    public function getListeAttente()
    {
        return $this->listeAttente;
    }

    /**
     * Set lyceen
     *
     * @param \CEC\MembreBundle\Entity\Eleve $lyceen
     * @return SortieEleve
     */
    public function setLyceen(\CEC\MembreBundle\Entity\Eleve $lyceen = null)
    {
        $this->lyceen = $lyceen;
    
        return $this;
    }

    /**
     * Get lyceen
     *
     * @return \CEC\MembreBundle\Entity\Eleve 
     */
    public function getLyceen()
    {
        return $this->lyceen;
    }

    /**
     * Set sortie
     *
     * @param \CEC\SecteurSortiesBundle\Entity\Sortie $sortie
     * @return SortieEleve
     */
    public function setSortie(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie = null)
    {
        $this->sortie = $sortie;
    
        return $this;
    }

    /**
     * Get sortie
     *
     * @return \CEC\SecteurSortiesBundle\Entity\Sortie 
     */
    public function getSortie()
    {
        return $this->sortie;
    }

    /**
     * Set presence
     *
     * @param boolean $presence
     * @return SortieEleve
     */
    public function setPresence($presence)
    {
        $this->presence = $presence;
    
        return $this;
    }

    /**
     * Get presence
     *
     * @return boolean 
     */
    public function getPresence()
    {
        return $this->presence;
    }
}