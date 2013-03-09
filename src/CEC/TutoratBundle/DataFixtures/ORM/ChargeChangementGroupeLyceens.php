<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\ChangementGroupeLyceen;
use CEC\MainBundle\Classes\AnneeScolaire;

class ChargeChangementGroupeLyceens extends AbstractFixture implements OrderedFixtureInterface
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
        
        $ref1 = new ChangementGroupeLyceen();
        $ref1->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementGroupeLyceen::CHANGEMENT_ACTION_AJOUT)
            ->setGroupe($this->getReference('jj_terminales_ancien'))
            ->setLyceen($this->getReference('medhi_amalou'))
            ->setLyceen($this->getReference('meissa_dieng'))
            ->setLyceen($this->getReference('tara_delpech'));
            
        $ref2 = new ChangementGroupeLyceen();
        $ref2->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementGroupeLyceen::CHANGEMENT_ACTION_SUPPRESSION)
            ->setGroupe($this->getReference('jj_terminales_ancien'))
            ->setLyceen($this->getReference('tara_delpech'));
            
        $ref3 = new ChangementGroupeLyceen();
        $ref3->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementGroupeLyceen::CHANGEMENT_ACTION_AJOUT)
            ->setGroupe($this->getReference('jj_terminales'))
            ->setLyceen($this->getReference('medhi_amalou'))
            ->setLyceen($this->getReference('meissa_dieng'))
            ->setLyceen($this->getReference('pauline_tampier'));
            
        $ref4 = new ChangementGroupeLyceen();
        $ref4->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementGroupeLyceen::CHANGEMENT_ACTION_AJOUT)
            ->setGroupe($this->getReference('mounier_montesquieu_secondes'))
            ->setLyceen($this->getReference('tara_delpech'));
            
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
        return 3;
    }
}
