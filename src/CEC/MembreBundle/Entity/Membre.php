<?php

namespace CEC\MembreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface
use Doctrine\ORM\Mapping as ORM;

/**
 * Membre
 */
class Membre implements UserInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $motDePasse;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \CEC\MembreBundle\Entity\Promotion
     */
    private $promotion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $secteurs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->secteurs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set prenom
     *
     * @param string $prenom
     * @return Membre
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Membre
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
     * Set motDePasse
     *
     * @param string $motDePasse
     * @return Membre
     */
    public function setMotDePasse($motDePasse)
    {
        $this->motDePasse = $motDePasse;
    
        return $this;
    }

    /**
     * Get motDePasse
     *
     * @return string 
     */
    public function getMotDePasse()
    {
        return $this->motDePasse;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Membre
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set promotion
     *
     * @param \CEC\MembreBundle\Entity\Promotion $promotion
     * @return Membre
     */
    public function setPromotion(\CEC\MembreBundle\Entity\Promotion $promotion = null)
    {
        $this->promotion = $promotion;
    
        return $this;
    }

    /**
     * Get promotion
     *
     * @return \CEC\MembreBundle\Entity\Promotion 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Add secteurs
     *
     * @param \CEC\MembreBundle\Entity\Secteur $secteurs
     * @return Membre
     */
    public function addSecteur(\CEC\MembreBundle\Entity\Secteur $secteurs)
    {
        $this->secteurs[] = $secteurs;
    
        return $this;
    }

    /**
     * Remove secteurs
     *
     * @param \CEC\MembreBundle\Entity\Secteur $secteurs
     */
    public function removeSecteur(\CEC\MembreBundle\Entity\Secteur $secteurs)
    {
        $this->secteurs->removeElement($secteurs);
    }

    /**
     * Get secteurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSecteurs()
    {
        return $this->secteurs;
    }
    
    
    // Méthodes personnalisées
    
    public function getRoles() {
        return array('ROLE_USER');
    }
    
    public function getPassword() {
        return $this->getMotDePasse();
    }
    
    public function getSalt() {
        return null;
    }
    
    public function getUsername() {
        return $this->getPrenom() . ' ' . $this->getNom();
    }
    
    public function eraseCredentials() {}
    
    public function equals(UserInterface $user) {
        return $this->getUsername == $user->getUsername;
    }
    
}
