<?php

namespace CEC\SecteurProjetsBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dossier
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\SecteurProjetsBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Album
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
	* @var \CEC\SecteurProjetsBundle\Entity\Projet
	*
	* @ORM\ManyToOne(targetEntity="\CEC\SecteurProjetsBundle\Entity\Projet", inversedBy="albums")
	* @ORM\JoinColumn(name="projet_id", referencedColumnName="id")
	*/
	private $projet;
	
	/**
	*
	* @var integer
	*
	* @ORM\Column(name="annee", type="integer")
	*/
	private $annee;
	
	/**
	*@var \Doctrine\Common\Collections\Collection
	*
	*@ORM\OneToMany(targetEntity="CEC\SecteurProjetsBundle\Entity\Image", mappedBy="album", cascade={"persist", "remove"})
	*/
	private $images;
	
	
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
     * Set projet
     *
	 * @var \CEC\SecteurProjetsBundle\Entity\Projet
     * @return Album
     */
    public function setProjet(\CEC\SecteurProjetsBundle\Entity\Projet $projet)
    {
        $this->projet = $projet;
    
        return $this;
    }

    /**
     * Get projet
     *
	 * @return \CEC\SecteurProjetsBundle\Entity\Projet
     */
    public function getProjet()
    {
        return $this->projet;
    }
	
	/**
     * Set annee
     *
     * @return Album
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
    
        return $this;
    }

    /**
     * Get annee
     *
     * @return integer 
     */
    public function getAnnee()
    {
        return $this->annee;
    }
	
	
	/**
     * Set images
     *
     * @return Album
     */
    public function setImages($images)
    {
        $this->images->clear();
        foreach($images as $image)
        {
            $this->images->add($image);
        }
    
        return $this;
    }

    /**
     * Get alt
     *
     * @return integer 
     */
    public function getImages()
    {
        return $this->images->toArray();
    }
	
	/**
	* Add image
	* @var \CEC\SecteurProjetsBundle\Entity\Image
	* @return Album
	*/
	public function addImage(\CEC\SecteurProjetsBundle\Entity\Image $image)
	{
		$this->images->add($image);
		return $this;
	}
	
	/**
	* Remove image
	* @var \CEC\SecteurProjetsBundle\Entity\Image
	* @return Album
    */
	public function removeImage(\CEC\SecteurProjetsBundle\Entity\Image $image)
	{
		$this->images->removeElement($image);
		return $this;
	}
}
