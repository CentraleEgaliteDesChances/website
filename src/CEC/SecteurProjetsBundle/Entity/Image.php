<?php

namespace CEC\SecteurProjetsBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use CEC\SecteurProjetsBundle\Entity\Album;
use CEC\SecteurProjetsBundle\Entity\Projet;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dossier
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\SecteurProjetsBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
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
	*
	*@ORM\Column(name="alt", type="string")
	*/
	private $alt;
	
	/**
	*
	* @ORM\Column(name="legende", type="string")
	*/
	private $legende;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;
	
	/**
     * @Assert\File(maxSize="10000000")
     */
    public $file;
	
	/**
	* @var \CEC\SecteurProjetsBundle\Entity\Album
	*
	* @ORM\ManyToOne(targetEntity="CEC\SecteurProjetsBundle\Entity\Album", inversedBy="photos")
	*/
	private $album;


	public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/images';
    }
	
	/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)).$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
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
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
	
	/**
     * Set file
     *
     * @return Image
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }
	
	
	/**
     * Set alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    
        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }
	
	/**
     * Set album
     *
     * @return Image
     */
    public function setAlbum(\CEC\SecteurProjetsBundle\Entity\Album $album)
    {
        $this->album = $album;
    
        return $this;
    }

    /**
     * Get album
     *
     * @return integer \CEC\SecteurProjetsBundle\Entity\Album
	 */
    public function getAlbum()
    {
        return $this->album;
    }
	
	/**
     * Set legende
     *
     * @return Image
     */
    public function setLegende($legende)
    {
        $this->legende = $legende;
    
        return $this;
    }

    /**
     * Get legende
     *
     * @return string
     */
    public function getLegende()
    {
        return $this->legende;
    }
}
