<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Cordee;

class ChargeCordees extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $open = new Cordee();
        $open->setNom('Cordée OPEN');
        
        $michelin = new Cordee();
        $michelin->setNom('Cordée Michelin');
        
        $leMoigne = new Cordee();
        $leMoigne->setNom('Cordée Le Moigne');
                
        $manager->persist($open);
        $manager->persist($michelin);
        $manager->persist($leMoigne);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1;
    }
}
