<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\ChangementGroupeTuteur;
use CEC\MainBundle\Classes\AnneeScolaire;

class ChargeChangementGroupeTuteurs extends AbstractFixture implements OrderedFixtureInterface
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
        
        $ref1 = new ChangementGroupeTuteur();
        $ref1->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementGroupeTuteur::CHANGEMENT_ACTION_AJOUT)
            ->setGroupe($this->getReference('jj_terminales_ancien'))
            ->setTuteur($this->getReference('pol_maire'));
            
        $ref2 = new ChangementGroupeTuteur();
        $ref2->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementGroupeTuteur::CHANGEMENT_ACTION_AJOUT)
            ->setGroupe($this->getReference('jj_terminales_ancien'))
            ->setTuteur($this->getReference('helene_sicsic'));
            
        $ref3 = new ChangementGroupeTuteur();
        $ref3->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementGroupeTuteur::CHANGEMENT_ACTION_SUPPRESSION)
            ->setGroupe($this->getReference('jj_terminales_ancien'))
            ->setTuteur($this->getReference('pol_maire'));
            
        $ref4 = new ChangementGroupeTuteur();
        $ref4->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementGroupeTuteur::CHANGEMENT_ACTION_AJOUT)
            ->setGroupe($this->getReference('jj_terminales'))
            ->setTuteur($this->getReference('pol_maire'));
            
        $ref5 = new ChangementGroupeTuteur();
        $ref5->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementGroupeTuteur::CHANGEMENT_ACTION_AJOUT)
            ->setGroupe($this->getReference('mounier_montesquieu_secondes'))
            ->setTuteur($this->getReference('pol_maire'))
            ->setTuteur($this->getReference('helene_sicsic'));
            
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
