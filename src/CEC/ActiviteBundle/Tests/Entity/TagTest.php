<?php

namespace CEC\ActiviteBundle\Tests\Entity;

use CEC\ActiviteBundle\Entity\Tag;
use CEC\ActiviteBundle\Entity\Activite;
use CEC\ActiviteBundle\Entity\CompteRendu;

class TagTest extends \PHPUnit_Framework_TestCase
{
    protected $tag;
    
    protected function setUp() {
        //On crée un nouveau tag de test.
        $this->tag = new Tag("Tag de test");
    }
    
    public function testCreate() {
        $this->assertInstanceOf('CEC\ActiviteBundle\Entity\Tag', new Tag("Tag de test"));
    }
    
    public function testValidation() {
        try 
        {
            // On teste que l'initialisation rate bien si aucun argument n'est passé à la création
            $tagFail = new Tag();

            //On teste qu'on ne peut bien rajouter que des activités au tag
            $this->tag->addActivite(new CompteRendu());

        } catch (\exception $e) {
            return;
        }
        $this->fail();
    }

    public function testGetters() {
        $tag = new Tag("Test Getter");

        $this->assertEquals('Test Getter', $tag->getContenu());
        $this->assertEmpty($tag->getActivites());
        
    }

    public function testSetters() {
        $tag = new Tag("Test");

        $tag->setContenu("Test Setter");
        $this->assertEquals('Test Setter', $tag->getContenu());

        $ac1 = new Activite();
        $ac1->setTitre("Test1");
        $ac2 = new Activite();
        $ac2->setTitre("Test2");
        $tag->addActivite($ac1);
        $tag->addActivite($ac2);
        $this->assertCount(2, $tag->getActivites());

        $tag->removeActivite($ac1);
        $this->assertCount(1, $tag->getActivites());
        $this->assertContainsOnly($ac2, $tag->getActivites());        
    }

}
