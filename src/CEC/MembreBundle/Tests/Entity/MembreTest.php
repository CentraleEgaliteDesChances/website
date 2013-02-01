<?php

namespace CEC\MembreBundle\Tests\Controller;

use CEC\MembreBundle\Entity\Membre;

class MembreTest extends \PHPUnit_Framework_TestCase
{
    public function provider() {
        return array(
            array('Jean-Baptiste', 'Bayle', 'jean-baptiste.bayle@gmail.com', 'testMotDePasse', '0608028325'),
            array('Karàktèr akçèntù~', 'Bayle', 'jean-baptiste.bayle@gmail.com', 'testMotDePasse', '0608028325'),
            array('Jean-Baptiste', '', 'jean-baptiste.bayle@gmail.com', 'testMotDePasse', '0608'),
            array('Jean-Baptiste', 'Bayle', 'jean-baptiste.baylegmail.com', 'mo', ''),
            array('Jean-Baptiste', 'Bayle', 'jean-baptiste.bayle@gmail.com', '', '06 08 02 83 25')
        );
    }
    
    /**
     * @dataProvider provider
     */
    public function testCreation($prenom, $nom, $email, $motDePasse, $telephone) {
        $membre = new Membre();
        $membre->setPrenom($prenom)
            ->setNom($nom)
            ->setEmail($email)
            ->setMotDePasse(sha1($motDePasse))
            ->setTelephone($telephone);
        
        // Test de l'entité
        $this->assertInstanceOf('CEC\MembreBundle\Entity\Membre', $membre);
        
        // Test des attributs
        $this->assertEquals($prenom, $membre->getPrenom());
        $this->assertEquals($nom, $membre->getNom());
        $this->assertEquals($email, $membre->getEmail());
        $this->assertEquals(sha1($motDePasse), $membre->getMotDePasse());
        $this->assertEquals($telephone, $membre->getTelephone());
        
        // Test du nom d'utilisateur
        $this->assertEquals($prenom . ' ' . $nom, $membre->getUsername());
        
        // Test des rôles
        $this->assertEquals(1, count($membre->getRoles()));
        $roles = $membre->getRoles();
        $this->assertEquals('ROLE_USER', $roles[0]);
    }
}
