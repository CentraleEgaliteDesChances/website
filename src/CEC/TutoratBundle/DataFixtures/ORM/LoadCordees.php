<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Cordee;

class LoadCordees extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $truc = new Cordee();
        $truc->setNom('Cordée Truc');
        
        $machin = new Cordee();
        $machin->setNom('Cordée Machin');
        
        $bidule = new Cordee();
        $bidule->setNom('Cordée Bidule');
                
        $manager->persist($truc);
        $manager->persist($machin);
        $manager->persist($bidule);
        $manager->flush();
        
        $this->addReference('truc', $truc);
        $this->addReference('machin', $machin);
        $this->addReference('bidule', $bidule);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 10;
    }
}
