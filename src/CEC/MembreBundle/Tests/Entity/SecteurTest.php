<?php

namespace CEC\MembreBundle\Tests\Controller;

use CEC\MembreBundle\Entity\Secteur;

class SecteurTest extends \PHPUnit_Framework_TestCase
{
    public function provider() {
        return array(
            array('Secteur Activités Scientifiques'),
            array('Secteur Fundraising'),
            array(''),
            array(2)
        );
    }
    
    /**
     * @dataProvider provider
     */
    public function testCreation($nom) {
        $secteur = new Secteur();
        $secteur->setNom($nom);
        
        $this->assertInstanceOf('CEC\MembreBundle\Entity\Secteur', $secteur);
        $this->assertEquals($nom, $secteur->getNom());
    }
}
