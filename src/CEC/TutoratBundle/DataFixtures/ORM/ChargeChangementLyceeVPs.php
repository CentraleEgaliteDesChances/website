<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\ChangementLyceeVP;
use CEC\MainBundle\Classes\AnneeScolaire;

class ChargeChangementLyceeVPs extends AbstractFixture implements OrderedFixtureInterface
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
        
        $ref1 = new ChangementLyceeVP();
        $ref1->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementLyceeVP::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('jj'))
            ->setVP($this->getReference('pol_maire'));
            
        $ref2 = new ChangementLyceeVP();
        $ref2->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementLyceeVP::CHANGEMENT_ACTION_SUPPRESSION)
            ->setLycee($this->getReference('jj'))
            ->setVP($this->getReference('pol_maire'));
            
        $ref3 = new ChangementLyceeVP();
        $ref3->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementLyceeVP::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('jj'))
            ->setVP($this->getReference('helene_sicsic'));
            
        $ref4 = new ChangementLyceeVP();
        $ref4->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementLyceeVP::CHANGEMENT_ACTION_AJOUT)
            ->setLycee($this->getReference('montesquieu'))
            ->setVP($this->getReference('pol_maire'));
            
        $manager->persist($ref1);
        $manager->persist($ref2);
        $manager->persist($ref3);
        $manager->persist($ref4);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }
}
