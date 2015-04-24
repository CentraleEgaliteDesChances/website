<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupeEleves
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\TutoratBundle\Entity\GroupeElevesRepository")
 */
class GroupeEleves
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
