<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\CordeeLyceeReference;

class ChargeCordeeLyceeReferences extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $ref1 = new CordeeLyceeReference();
        $ref1->setAnnee('2013')
            ->setLycee($this->getReference('jj'))
            ->setCordee($this->getReference('michelin'));
        
        $ref2 = new CordeeLyceeReference();
        $ref2->setAnnee('2013')
            ->setLycee($this->getReference('mounier'))
            ->setCordee($this->getReference('open'));
        
        $ref3 = new CordeeLyceeReference();
        $ref3->setAnnee('2013')
            ->setLycee($this->getReference('montesquieu'))
            ->setCordee($this->getReference('open'));
        
        $ref4 = new CordeeLyceeReference();
        $ref4->setAnnee('2012')
            ->setLycee($this->getReference('vilgenis'))
            ->setCordee($this->getReference('michelin'));
            
        $ref5 = new CordeeLyceeReference();
        $ref5->setAnnee('2012')
            ->setLycee($this->getReference('ginette'))
            ->setCordee($this->getReference('michelin'));
            
        $manager->persist($ref1);
        $manager->persist($ref2);
        $manager->persist($ref3);
        $manager->persist($ref4);
        $manager->persist($ref5);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 3;
    }
}
