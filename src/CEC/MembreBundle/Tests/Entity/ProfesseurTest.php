<?php

namespace CEC\MembreBundle\Tests\Entity;

use CEC\MembreBundle\Entity\Professeur;

use CEC\TutoratBundle\Entity\Lycee;

class ProfesseurTest extends \PHPUnit_Framework_TestCase
{
    protected $professeur;
    
    protected function setUp() {
        //On crée un nouveau professeur de test.
        $this->professeur = new Professeur();
        $this->professeur->setPrenom('Professeur')
                ->setNom('Test')
                ->setMail('prof.test@gmail.com')
                ->setLycee(new Lycee())
                ->setRole('proviseur')
                ->setTelephoneFixe('0203040506')
                ->setTelephonePortable('0607080910');
    }
    
    public function testCreate() {
        $this->assertInstanceOf('CEC\MembreBundle\Entity\Professeur', new Professeur());
    }

    public function testUpdateRoles()
    {
        $prof = $this->professeur;

        $this->assertEquals(array('ROLE_PROFESSEUR'), $prof->getRoles());

        $prof->updateRoles();

        $this->assertEquals(array('ROLE_PROFESSEUR', 'ROLE_PROVISEUR'), $prof->getRoles());

        $prof->setRole('enseignant')->updateRoles();

        $this->assertEquals(array('ROLE_PROFESSEUR'), $prof->getRoles());

        $prof->setReferent($prof->getLycee())->updateRoles();

        $this->assertEquals(array('ROLE_PROFESSEUR', 'ROLE_PROFESSEUR_REFERENT'), $prof->getRoles());
    }

}
