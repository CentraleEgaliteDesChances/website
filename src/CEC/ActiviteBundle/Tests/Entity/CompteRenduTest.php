<?php

namespace CEC\ActiviteBundle\Tests\Entity;

use CEC\ActiviteBundle\Entity\CompteRendu;
use CEC\ActiviteBundle\Entity\Activite;

use CEC\TutoratBundle\Entity\Seance;

use CEC\MembreBundle\Entity\Membre;
use CEC\MembreBundle\Entity\Eleve;

class CompteRenduTest extends \PHPUnit_Framework_TestCase
{
    protected $compteRendu;
    
    protected function setUp() {
        //On crée un nouveau compteRendu de test.
        $this->compteRendu = new CompteRendu();
        $this->compteRendu->setNoteContenu(4)
            ->setNoteInteractivite(3)
            ->setNoteAtteinteObjectifs(2)
            ->setDureeAdaptee(1)
            ->setCommentaires('Test');
            
        $seance = new Seance();
        $this->compteRendu->setSeance($seance);
        $auteur = new Membre();
        $this->compteRendu->setAuteur($auteur);
        $ac = new Activite();
        $this->compteRendu->setActivite($ac);
    }
    
    public function testCreate() {
        $this->assertInstanceOf('CEC\ActiviteBundle\Entity\CompteRendu', new CompteRendu());
    }

    public function testGetters() {
        $compteRendu = $this->compteRendu;

        $this->assertEquals(4, $compteRendu->getNoteContenu());
        $this->assertEquals(3, $compteRendu->getNoteInteractivite());
        $this->assertEquals(2, $compteRendu->getNoteAtteinteObjectifs());
        $this->assertEquals(1, $compteRendu->getDureeAdaptee());
        $this->assertEquals('Test', $compteRendu->getCommentaires());
        $this->assertInstanceOf('CEC\TutoratBundle\Entity\Seance', $compteRendu->getSeance());
        $this->assertInstanceOf('CEC\MembreBundle\Entity\Membre', $compteRendu->getAuteur());
        $this->assertInstanceOf('CEC\ActiviteBundle\Entity\Activite', $compteRendu->getActivite());
        $this->AssertFalse($compteRendu->getLu());
        
    }

    public function testSetters() {
        $compteRendu = $this->compteRendu;

        $compteRendu->setNoteContenu(1);
        $compteRendu->setNoteInteractivite(2);
        $compteRendu->setNoteAtteinteObjectifs(5);

        $this->assertEquals(1, $compteRendu->getNoteContenu());
        $this->assertEquals(2, $compteRendu->getNoteInteractivite());
        $this->assertEquals(5, $compteRendu->getNoteAtteinteObjectifs());

        $compteRendu->setDureeAdaptee(-1);

        $this->assertEquals(-1, $compteRendu->getDureeAdaptee());

        $compteRendu->setCommentaires('Test commentaires');

        $this->assertEquals('Test commentaires', $compteRendu->getCommentaires());

        $newAuteur = new Membre();
        $compteRendu->setAuteur($newAuteur);
        $newSeance = new Seance();
        $compteRendu->setSeance($newSeance);
        $newAc = new Activite();
        $compteRendu->setActivite($newAc);

        $this->assertEquals($newAuteur, $compteRendu->getAuteur());
        $this->assertEquals($newSeance, $compteRendu->getSeance());
        $this->assertEquals($newAc, $compteRendu->getActivite());

        $compteRendu->setLu(true);

        $this->assertTrue($compteRendu->getLu());
    }


    public function testCompteRenduRedige()
    {
        $compteRendu = $this->compteRendu;

        $this->AssertFalse($compteRendu->isRedige());

        $lyceen = new Eleve();
        $seance = new Seance();
        $seance->addLyceen($lyceen);

        $compteRendu->setSeance($seance);

        $this->assertTrue($compteRendu->isRedige());

        $compteRendu->setNoteContenu(null);

        $this->AssertFalse($compteRendu->isRedige());
    }

    public function testGetNoteGlobale()
    {
        $compteRendu = $this->compteRendu;

        $compteRendu->setNoteContenu(null);

        $this->AssertFalse($compteRendu->getNoteGlobale());

        $compteRendu->setNoteContenu(4);

        $this->assertEquals(3, $compteRendu->getNoteGlobale());
    }

}
