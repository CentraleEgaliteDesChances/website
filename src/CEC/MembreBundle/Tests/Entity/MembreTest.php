<?php

namespace CEC\MembreBundle\Tests\Controller;

use CEC\MembreBundle\Entity\Membre;

class MembreTest extends \PHPUnit_Framework_TestCase
{
    public function provider() {
        return array(
            array('Jean-Baptiste', 'Bayle', 'jean-baptiste.bayle@gmail.com', 'testMotDePasse'),
            array('Karàktèr akçèntù~', 'Bayle', 'jean-baptiste.bayle@gmail.com', 'testMotDePasse'),
            array('Jean-Baptiste', '', 'jean-baptiste.bayle@gmail.com', 'testMotDePasse'),
            array('Jean-Baptiste', 'Bayle', 'jean-baptiste.baylegmail.com', 'mo'),
            array('Jean-Baptiste', 'Bayle', 'jean-baptiste.bayle@gmail.com', '')
        );
    }
    
    /**
     * @dataProvider provider
     */
    public function testCreation($prenom, $nom, $email, $motDePasse) {
        $membre = new Membre();
        $membre->setPrenom($prenom)
            ->setNom($nom)
            ->setEmail($email)
            ->setMotDePasse(sha1($motDePasse));
        
        $this->assertInstanceOf('CEC\MembreBundle\Entity\Membre', $membre);
        
        $this->assertEquals($prenom, $membre->getPrenom());
        $this->assertEquals($nom, $membre->getNom());
        $this->assertEquals($email, $membre->getEmail());
        $this->assertEquals(sha1($motDePasse), $membre->getMotDePasse());
    }
}
