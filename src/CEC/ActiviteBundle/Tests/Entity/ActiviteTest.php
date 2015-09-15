<?php

namespace CEC\ActiviteBundle\Tests\Entity;

use CEC\ActiviteBundle\Entity\Tag;
use CEC\ActiviteBundle\Entity\Activite;
use CEC\ActiviteBundle\Entity\CompteRendu;
use CEC\ActiviteBundle\Entity\Document;

class ActiviteTest extends \PHPUnit_Framework_TestCase
{
    protected $activite;
    
    protected function setUp() {
        //On crée un nouveau activite de test.
        $this->activite = new Activite();
        $this->activite->setTitre('Acti Test')
            ->setDescription('Description Test')
            ->setDuree('1h15m')
            ->setType('Activité Culturelle');
    }
    
    public function testCreate() {
        $this->assertInstanceOf('CEC\ActiviteBundle\Entity\Activite', new Activite());
    }
    

    public function testGetters() {
        $activite = $this->activite;

        $this->assertEquals('Acti Test', $activite->getTitre());
        $this->assertEquals('Description Test', $activite->getDescription());
        $this->assertEquals('1h15m', $activite->getDuree());
        $this->assertEquals('Activité Culturelle', $activite->getType());
        $this->assertCount(0, $activite->getTags());
        $this->assertCount(0, $activite->getVersions());
        $this->assertCount(0, $activite->getCompteRendus());
        
    }

    public function testSetters() {
        $activite = $this->activite;

        $activite->setTitre("Test Setter");
        $this->assertEquals('Test Setter', $activite->getTitre());

        $activite->setDescription('Test Description');
        $this->assertEquals('Test Description', $activite->getDescription());

        $activite->setDuree('Trop long');
        $this->assertEquals('Trop long', $activite->getDuree());

        $activite->setType('Activité Scientifique');
        $this->assertEquals('Activité Scientifique', $activite->getType());

        $tag = new Tag('Test1');
        $activite->addTag(new Tag('Test2'))->addTag(new Tag('Test3'))->addTag($tag);
        $this->assertCount(3, $activite->getTags());

        $activite->removeTag($tag);
        $this->assertCount(2, $activite->getTags());

        $document = new Document();
        $activite->addVersion(new Document())->addVersion($document);
        $this->assertCount(2, $activite->getVersions());

        $activite->removeVersion($document);
        $this->assertCount(1, $activite->getVersions());

        $cr = new CompteRendu();
        $activite->addCompteRendu(new CompteRendu())->addCompteRendu(new CompteRendu())->addCompteRendu($cr);
        $this->assertCount(3, $activite->getCompteRendus());

        $activite->removeCompteRendu($cr);
        $this->assertCount(2, $activite->getCompteRendus());
       
    }

}
