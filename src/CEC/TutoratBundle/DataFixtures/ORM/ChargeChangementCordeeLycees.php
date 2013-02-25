<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\ChangementCordeeLycee;
use CEC\MainBundle\Classes\AnneeScolaire;

class ChargeChangementCordeeLycees extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $anneeScolaire = new AnneeScolaire();
        
        $ref1 = new ChangementCordeeLycee();
        $ref1->setAnnee($anneeScolaire->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('jj'))
            ->setCordee($this->getReference('michelin'));
        
        $ref2 = new ChangementCordeeLycee();
        $ref2->setAnnee($anneeScolaire->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('mounier'))
            ->setCordee($this->getReference('open'));
        
        $ref3 = new ChangementCordeeLycee();
        $ref3->setAnnee($anneeScolaire->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('montesquieu'))
            ->setCordee($this->getReference('open'));
        
        $ref4 = new ChangementCordeeLycee();
        $ref4->setAnnee($anneeScolaire->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('vilgenis'))
            ->setCordee($this->getReference('michelin'));
            
        $ref5 = new ChangementCordeeLycee();
        $ref5->setAnnee($anneeScolaire->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
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
