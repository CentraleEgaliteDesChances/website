<?php

namespace CEC\MembreBundle\Utility;

use CEC\MembreBundle\Entity\Membre;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Représente un nouveau membre du buro, utilisé sur la page des passations pour accorder
 * à une nouveau membre les privilèges des membres du buro.
 * Cette classe n'est pas persistée.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 */
class NouveauMembreBuro
{
    /**
     * Membre du buro.
     * 
     * @var CEC\MembreBundle\Entity\Membre
     * @Assert\Valid
     * @Assert\NotBlank(message = "Merci de sélectionner un membre pour lui accorder les privilèges du buro.")
     */
    private $membre;
    
    
    public function getMembre() {
        return $this->membre;
    }
    
    public function setMembre(Membre $membre) {
        $this->membre = $membre;
        return $this;
    }
}