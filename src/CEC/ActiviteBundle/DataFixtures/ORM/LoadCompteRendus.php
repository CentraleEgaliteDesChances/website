<?php

namespace CEC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\ActiviteBundle\Entity\CompteRendu;

class LoadCompteRendus extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\TutoratBundle\DataFixtures\ORM\LoadSeances',
        );
    }
}
