<?php

namespace CEC\ActiviteBundle\Tests\Entity;

use CEC\ActiviteBundle\Entity\QuizzActu;

use CEC\MembreBundle\Entity\Membre;

class QuizzActuTest extends \PHPUnit_Framework_TestCase
{
    protected $quizzActu;
    
    protected function setUp() {
        //On crée un nouveau quizzActu de test.
        $this->quizzActu = new QuizzActu();

        $membreTest = new Membre();
        $membreTest->setPrenom("Membre")->setNom('Test');
        $this->quizzActu->setAuteur($membreTest);
        $this->quizzActu->setSemaine(new \DateTime('2015-06-15'))
            ->setCommentaire('Commentaire Test')
            ->setNomFichierPDF('15-06-2015.pdf');
    }
    
    public function testCreate() {
        $this->assertInstanceOf('CEC\ActiviteBundle\Entity\QuizzActu', new QuizzActu());
    }
    

    public function testGetters() {
        $quizzActu = $this->quizzActu;

        $this->assertEquals('Membre Test', $quizzActu->getAuteur()->getUsername());
        $this->assertEquals(new \DateTime('2015-06-15'), $quizzActu->getSemaine());
        $this->assertEquals('Commentaire Test', $quizzActu->getCommentaire());
        $this->assertEquals('15-06-2015.pdf', $quizzActu->getNomFichierPDF());

    }

    public function testSetters() {
        $quizzActu = $this->quizzActu;

        $newMembre = new Membre();
        $newMembre->setPrenom("Test")->setNom("Membre");
        $quizzActu->setAuteur($newMembre);

        $this->assertEquals('Test Membre', $quizzActu->getAuteur()->getUsername());

        $quizzActu->setSemaine($quizzActu->getSemaine()->sub(new \DateInterval('P7D')));
        $this->assertEquals(new \DateTime('2015-06-08'), $quizzActu->getSemaine());

        $quizzActu->setCommentaire('Test');
        $this->assertEquals('Test', $quizzActu->getCommentaire());

        $quizzActu->setNomFichierPDF('test.pdf');
        $this->assertEquals('test.pdf', $quizzActu->getNomFichierPDF());


    }

    public function testDateDuPremierJourDeLaSemaine()
    {

        $quizzActu = $this->quizzActu;

        $this->assertTrue($quizzActu->isSemaineValid());

        $quizzActu->setSemaine($quizzActu->getSemaine()->add(new \DateInterval('P1D')));
        $this->assertFalse($quizzActu->isSemaineValid());



    }

    public function testGetterUpload()
    {
        $quizzActu = $this->quizzActu;

        $this->assertEquals('/uploads/quizzActus', $quizzActu->getDossierTelechargement());

        $this->assertEquals('/uploads/quizzActus/15-06-2015.pdf', $quizzActu->getCheminPDF());

    }

    public function testToString()
    {
        $quizzActu = $this->quizzActu;

        $this->assertEquals('Semaine du 15 au 21 juin 2015', $quizzActu->__toString());
    }
}
