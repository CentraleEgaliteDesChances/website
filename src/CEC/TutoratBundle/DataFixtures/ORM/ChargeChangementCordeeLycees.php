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
        $annee2013 = new AnneeScolaire();
        $annee2013->setAnneeScolaire('2012');
        $annee2010 = new AnneeScolaire();
        $annee2010->setAnneeScolaire('2010');
        
        $ref1 = new ChangementCordeeLycee();
        $ref1->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('jj'))
            ->setCordee($this->getReference('michelin'));
        
        $ref2 = new ChangementCordeeLycee();
        $ref2->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('mounier'))
            ->setCordee($this->getReference('open'));
        
        $ref3 = new ChangementCordeeLycee();
        $ref3->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('montesquieu'))
            ->setCordee($this->getReference('open'));
        
        $ref4 = new ChangementCordeeLycee();
        $ref4->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('vilgenis'))
            ->setCordee($this->getReference('michelin'));
            
        $ref5 = new ChangementCordeeLycee();
        $ref5->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_SUPPRESSION)
            ->setLycee($this->getReference('vilgenis'))
            ->setCordee($this->getReference('michelin'));
            
        $ref6 = new ChangementCordeeLycee();
        $ref6->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('ginette'))
            ->setCordee($this->getReference('michelin'));
            
        $manager->persist($ref1);
        $manager->persist($ref2);
        $manager->persist($ref3);
        $manager->persist($ref4);
        $manager->persist($ref5);
        $manager->persist($ref6);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }
}
