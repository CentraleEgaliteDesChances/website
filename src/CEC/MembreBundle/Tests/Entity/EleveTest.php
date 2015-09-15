<?php

namespace CEC\MembreBundle\Tests\Entity;

use CEC\MembreBundle\Entity\Eleve;

use CEC\TutoratBundle\Entity\Lycee;
use CEC\TutoratBundle\Entity\Seance;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\TutoratBundle\Entity\GroupeEleves;

use CEC\SecteurProjetsBundle\Entity\Reunion;
use CEC\SecteurProjetsBundle\Entity\ProjetEleve;

use CEC\SecteurSortiesBundle\Entity\SortieEleve;
use CEC\SecteurSortiesBundle\Entity\Sortie;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class EleveTest extends \PHPUnit_Framework_TestCase
{
    protected $lyceen;
    
    protected function setUp() {
        //On crée un nouveau lycéen de test.
        $this->lyceen = new Eleve();
        $lyceen = $this->lyceen;

        // Initialisation des données
        $lyceen->setNom("Test");
        $lyceen->setPrenom("Eleve");
        $lyceen->setMail("eleve.test@gmail.com");
        $lyceen->setTelephone('0607080910');
        $lyceen->setAdresse('7 rue du Test');
        $lyceen->setCodePostal('49000');
        $lyceen->setVille('Test Ville');
        $lyceen->setNomPere('Père Test');
        $lyceen->setNomMere('Mère Test');
        $lyceen->setTelephoneParent('0203040506');
        $lyceen->setDatenaiss('2000-01-01');
        $lyceen->setMotDePasse('test');
    }
    
    public function testCreate() {
        $this->assertInstanceOf('CEC\MembreBundle\Entity\Eleve', new Eleve());
    }

    public function testGetters() {
        $lyceen = $this->lyceen;

        $this->assertEquals('Test', $lyceen->getNom());
        $this->assertEquals('Eleve', $lyceen->getPrenom());
        $this->assertEquals('eleve.test@gmail.com', $lyceen->getMail());
        $this->assertEquals('0607080910', $lyceen->getTelephone());
        $this->assertFalse($lyceen->getTelephonePublic());
        $this->assertEquals('7 rue du Test', $lyceen->getAdresse());
        $this->assertEquals('49000', $lyceen->getCodePostal());
        $this->assertEquals('Test Ville', $lyceen->getVille());
        $this->assertEquals('Père Test', $lyceen->getNomPere());
        $this->assertEquals('Mère Test', $lyceen->getNomMere());
        $this->assertEquals('0203040506', $lyceen->getTelephoneParent());
        $this->assertEquals('2000-01-01', $lyceen->getDatenaiss());
        $this->assertEquals('test', $lyceen->getMotDePasse());
        $this->assertTrue($lyceen->getCheckMail());

        $lyceen->addSeance(new Seance())->addSeance(new Seance())->addSeance(new Seance());

        $this->assertCount(3, $lyceen->getSeances());

        $lyceen->addSortie(new Sortie())->addSortie(new Sortie());

        $this->assertCount(2, $lyceen->getSorties());

        $lyceen->addProjetsParAnnee(new ProjetEleve())->addProjetsParAnnee(new ProjetEleve());

        $this->assertCount(2, $lyceen->getProjetsParAnnee());

        $lyceen->addReunion(new Reunion());

        $this->assertCount(1, $lyceen->getReunions());

        $groupe = new Groupe();
        $groupe->setNiveau('Groupe Test');

        $participation = new GroupeEleves();
        $participation->setGroupe($groupe);
        $participation->setAnneeScolaire(AnneeScolaire::withDate());
        $participation->setLyceen($lyceen);

        $lyceen->addGroupeParAnnee($participation);

        $this->assertEquals('Groupe Test', $lyceen->getGroupe()->getNiveau());
        
    }

    public function testUpdateRoles()
    {
        $lyceen = $this->lyceen;

        $this->assertEquals(array('ROLE_ELEVE'), $lyceen->getRoles());

        $lyceen->setDelegue(new Lycee())->updateRoles();
        $this->assertEquals(array('ROLE_ELEVE', 'ROLE_ELEVE_DELEGUE'), $lyceen->getRoles());

        $lyceen->setDelegue(null)->updateRoles();
        $this->assertEquals(array('ROLE_ELEVE'), $lyceen->getRoles());

    }

}
