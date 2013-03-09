<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\ChangementEnseignantLycee;
use CEC\MainBundle\Classes\AnneeScolaire;

class ChargeChangementEnseignantLycees extends AbstractFixture implements OrderedFixtureInterface
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
        
        $ref1 = new ChangementEnseignantLycee();
        $ref1->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementEnseignantLycee::CHANGEMENT_ACTION_AJOUT)
            ->setEnseignant($this->getReference('guillou'))
            ->setLycee($this->getReference('jj'))
            ->setRole('Chef d\'établissement');
        
        $ref2 = new ChangementEnseignantLycee();
        $ref2->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementEnseignantLycee::CHANGEMENT_ACTION_AJOUT)
            ->setEnseignant($this->getReference('merlet'))
            ->setLycee($this->getReference('jj'))
            ->setRole('Proviseur-adjoint');
            
        $ref3 = new ChangementEnseignantLycee();
        $ref3->setAnnee($annee2013->getAnneeScolaire())
            ->setAction(ChangementEnseignantLycee::CHANGEMENT_ACTION_SUPPRESSION)
            ->setEnseignant($this->getReference('guillou'))
            ->setRole('Chef d\'établissement')
            ->setLycee($this->getReference('jj'));
            
        $ref4 = new ChangementEnseignantLycee();
        $ref4->setAnnee($annee2010->getAnneeScolaire())
            ->setAction(ChangementEnseignantLycee::CHANGEMENT_ACTION_AJOUT)
            ->setEnseignant($this->getReference('laine'))
            ->setLycee($this->getReference('jj'))
            ->setRole('Professeur référent');
            
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
