<?php

namespace CEC\MembreBundle\Tests\Entity;

use CEC\MembreBundle\Entity\Secteur;
use CEC\MembreBundle\Entity\Membre;
use CEC\MembreBundle\Entity\Eleve;

class TagTest extends \PHPUnit_Framework_TestCase
{
    protected $secteur;
    
    protected function setUp() {
        //On crée un nouveau secteur de test.
        $this->secteur = new Secteur();
    }
    
    public function testCreate() {
        $this->assertInstanceOf('CEC\MembreBundle\Entity\Secteur', new Secteur());
    }

    public function testGetters() {
        $secteur = new Secteur();

        $this->assertEquals( null, $secteur->getNom());
        $this->assertEquals(0, count($secteur->getMembres()));
        
    }

    public function testSetters() {
        $secteur = new Secteur();

        $secteur->setNom('Test');
        $this->assertEquals('Test', $secteur->getNom());

        $m1 = new Membre();
        $m2 = new Membre();

        $secteur->addMembre($m1);
        $secteur->addMembre($m2);

        $this->assertEquals(2, count($secteur->getMembres()));

        $secteur->removeMembre($m2);

        $this->assertContainsOnly($m1, $secteur->getMembres());       
    }

}
