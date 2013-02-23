<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LyceenGroupeReference
 */
class LyceenGroupeReference
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $classe;


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
     * Set classe
     *
     * @param string $classe
     * @return LyceenGroupeReference
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;
    
        return $this;
    }

    /**
     * Get classe
     *
     * @return string 
     */
    public function getClasse()
    {
        return $this->classe;
    }
}