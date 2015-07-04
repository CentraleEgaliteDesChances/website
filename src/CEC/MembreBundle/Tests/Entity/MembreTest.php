<?php

namespace CEC\MembreBundle\Tests\Entity;

use CEC\MembreBundle\Entity\Membre;
use CEC\MembreBundle\Entity\Secteur;

use CEC\TutoratBundle\Entity\Lycee;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\TutoratBundle\Entity\Seance;
use CEC\TutoratBundle\Entity\GroupeTuteurs;

use CEC\SecteurProjetsBundle\Entity\Projet;

use CEC\ActiviteBundle\Entity\QuizzActu;
use CEC\ActiviteBundle\Entity\CompteRendu;
use CEC\ActiviteBundle\Entity\Document;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class MembreTest extends \PHPUnit_Framework_TestCase
{
    protected $tuteur;
    
    protected function setUp() {
        //On crée un nouveau lycéen de test.
        $this->tuteur = new Membre();
        $tuteur = $this->tuteur;

        // Initialisation des données
        $tuteur->setNom("Test");
        $tuteur->setPrenom("Membre");
        $tuteur->setMail("membre.test@gmail.com");
        $tuteur->setTelephone('0607080910');
        $tuteur->setMotDePasse('test');
        $tuteur->setPromotion(2016); // La meilleure <3

    }
    
    public function testCreate() {
        $this->assertInstanceOf('CEC\MembreBundle\Entity\Membre', new Membre());
    }

    public function testGetters() {
        $tuteur = $this->tuteur;

        $this->assertEquals('Test', $tuteur->getNom());
        $this->assertEquals('Membre', $tuteur->getPrenom());
        $this->assertEquals('membre.test@gmail.com', $tuteur->getMail());
        $this->assertEquals('0607080910', $tuteur->getTelephone());
        $this->assertEquals(2016, $tuteur->getPromotion());
        $this->assertFalse($tuteur->getBuro());
        $this->assertEquals('test', $tuteur->getMotDePasse());
        $this->assertTrue($tuteur->getCheckMail());
        $this->assertEmpty($tuteur->getLyceesPourVP());
        $this->assertEmpty($tuteur->getContactProjets());
        $this->assertEmpty($tuteur->getSeances());
        $this->assertEmpty($tuteur->getCompteRendus());
        $this->assertEmpty($tuteur->getQuizzActus());
        $this->assertEmpty($tuteur->getDocuments());
        $this->assertEmpty($tuteur->getSecteurs());
        $this->assertEmpty($tuteur->getGroupeParAnnee());


        
    }

    public function testSetters() {
        $tuteur = $this->tuteur;

        $projet = new Projet();
        $projetBis = new Projet();
        $tuteur->addContactProjet($projet)->addContactProjet(new Projet());
        $this->assertEquals(2, count($tuteur->getContactProjets()));

        $tuteur->removeContactProjet($projet);
        $this->assertContainsOnly($projetBis, $tuteur->getContactProjets());

        $lycee = new Lycee();
        $lyceeBis = new Lycee();
        $tuteur->addLyceesPourVP($lycee)->addLyceesPourVP($lyceeBis);
        $this->assertEquals(2, count($tuteur->getLyceesPourVP()));

        $tuteur->removeLyceesPourVP($lyceeBis);
        $this->assertContainsOnly($lycee, $tuteur->getLyceesPourVP());


        $seance = new Seance();
        $tuteur->addSeance($seance)->addSeance(new Seance())->addSeance(new Seance());
        $this->assertEquals(3, count($tuteur->getSeances()));

        $tuteur->removeSeance($seance);
        $this->assertEquals(2, count($tuteur->getSeances()));

        $groupe = new Groupe();
        $groupe->setNiveau('Groupe Test');

        $participation = new GroupeTuteurs();
        $participation->setGroupe($groupe);
        $participation->setAnneeScolaire(AnneeScolaire::withDate());
        $participation->setTuteur($tuteur);

        $tuteur->addGroupeParAnnee($participation);

        $this->assertEquals('Groupe Test', $tuteur->getGroupe()->getNiveau());

        $tuteur->removeGroupeParAnnee($participation);
        $this->assertEmpty($tuteur->getGroupeParAnnee());

        $document = new Document();
        $tuteur->addDocument($document);
        $this->assertContainsOnly($document, $tuteur->getDocuments());

        $tuteur->removeDocument($document);
        $this->assertEmpty($tuteur->getDocuments());

        $compteRendu = new CompteRendu();
        $tuteur->addCompteRendu($compteRendu);
        $this->assertContainsOnly($compteRendu, $tuteur->getCompteRendus());

        $tuteur->removeCompteRendu($compteRendu);
        $this->assertEmpty($tuteur->getCompteRendus());

        $quizzActu = new QuizzActu();
        $tuteur->addQuizzActu($quizzActu);
        $this->assertEquals(array($quizzActu), $tuteur->getQuizzActus());

        $tuteur->removeQuizzActu($quizzActu);
        $this->assertEmpty($tuteur->getQuizzActus());

        $secteur = new Secteur();
        $secteur->setNom('Secteur Test');
        $tuteur->addSecteur($secteur);
        $this->assertContainsOnly($secteur, $tuteur->getSecteurs());

        $tuteur->removeSecteur($secteur);
        $this->assertEmpty($tuteur->getSecteurs());

    }

    public function dataProviderRoles()
    {
        $actiSci = new Secteur();
        $actiSci->setNom("Secteur Activités Scientifiques");
        $actiCu = new Secteur();
        $actiCu->setNom("Secteur Activités Culturelles");
        $fund = new Secteur();
        $fund->setNom('Secteur Fundraising');
        $ev = new Secteur();
        $ev->setNom('Secteur Evènements');
        $gml = new Secteur();
        $gml->setNom("Secteur Good Morning London");
        $prepa = new Secteur();
        $prepa->setNom('Secteur Centrale Prépa');
        $fe = new Secteur();
        $fe->setNom('Secteur Focus Europe');
        $st = new Secteur();
        $st->setNom('Secteur Stage Théâtre');
        $art = new Secteur();
        $art->setNom('Secteur (Art)cessible');
        $ge = new Secteur();
        $ge->setNom('Secteur Geek');
        $sa = new Secteur();
        $sa->setNom('Secteur Saclay');
        $eu = new Secteur();
        $eu->setNom('Secteur Europen');
        $so = new Secteur();
        $so->setNom('Secteur Sorties');


        return array(
            array(array('ROLE_TUTEUR', 'ROLE_SECTEUR_ACTIS_SCIENTIFIQUES'), $actiSci),
            array(array('ROLE_TUTEUR', 'ROLE_SECTEUR_ACTIS_CULTURELLES'), $actiCu),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_FUNDRAISING"), $fund),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_EVCOM"), $ev),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_GEEK"), $ge),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_SACLAY"), $sa),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_EUROPEN"), $eu),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_SORTIES"), $so),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_GML", 'ROLE_SECTEUR_PROJETS'), $gml),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_PREPA", 'ROLE_SECTEUR_PROJETS'), $prepa),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_FOCUS_EUROPE", 'ROLE_SECTEUR_PROJETS'), $fe),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_THEATRE", 'ROLE_SECTEUR_PROJETS'), $st),
            array(array('ROLE_TUTEUR', "ROLE_SECTEUR_ARTCESSIBLE", 'ROLE_SECTEUR_PROJETS'), $art)
            );
    }

    /**
    * @dataProvider dataProviderRoles
    */
    public function testUpdateRoles($expectedRoles, $secteur)
    {

        $tuteur = $this->tuteur;

        $this->assertEquals(array('ROLE_TUTEUR'), $tuteur->getRoles());

        $tuteur->setBuro(true)->updateRoles();
        $this->assertEquals(array('ROLE_TUTEUR', 'ROLE_BURO'), $tuteur->getRoles());

        $tuteur->setBuro(false)->updateRoles();

        $tuteur->addLyceesPourVP(new Lycee())->updateRoles();
        $this->assertEquals(array('ROLE_TUTEUR', 'ROLE_VP_LYCEE'), $tuteur->getRoles());

        // On vérifie que si on en ajoute un deuxieme, le role n'apparait bien qu'une fois.
        $tuteur->addLyceesPourVP(new Lycee())->updateRoles();
        $this->assertEquals(array('ROLE_TUTEUR', 'ROLE_VP_LYCEE'), $tuteur->getRoles());

        foreach($tuteur->getLyceesPourVP() as $l)
        {
            $tuteur->removeLyceesPourVP($l);
        }

        $tuteur->updateRoles();
        $this->assertEquals(array('ROLE_TUTEUR'), $tuteur->getRoles());

        $tuteur->addSecteur($secteur)->updateRoles();
        $this->assertEquals($expectedRoles, $tuteur->getRoles());

        $tuteur->removeSecteur($secteur)->updateRoles();
        $this->assertEquals(array('ROLE_TUTEUR'), $tuteur->getRoles());


    }

}