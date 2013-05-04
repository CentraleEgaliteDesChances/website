<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Groupe;


class LoadGroupes extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $debut = new \DateTime('2013-03-12 17:30:00');
        $fin = new \DateTime('2013-03-12 19:30:00');
        
        $jj_premieres = new Groupe();
        $jj_premieres->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut($debut)
            ->setFin($fin)
            ->setRendezVous('aux barrières');
            
        $jj_terminales = new Groupe();
        $jj_terminales->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut($debut)
            ->setFin($fin)
            ->setRendezVous('aux barrières');
            
        $jj_terminales_ancien = new Groupe();
        $jj_terminales_ancien->setNiveau('Premières et terminales')
            ->setTypeDeTutorat('Tutorat Culturel et Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut($debut)
            ->setFin($fin)
            ->setRendezVous('aux barrières');
            
        $mounier_montesquieu_secondes = new Groupe();
        $mounier_montesquieu_secondes->setNiveau('Secondes')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut($debut)
            ->setFin($fin)
            ->setRendezVous('à l\'entrée Nord du Bat\'ens');
            
        $jj_premieres->addLycee($this->getReference('jj'));
        $jj_terminales->addLycee($this->getReference('jj'));
        $jj_terminales_ancien->addLycee($this->getReference('jj'));
        $mounier_montesquieu_secondes->addLycee($this->getReference('mounier'));
        $mounier_montesquieu_secondes->addLycee($this->getReference('montesquieu'));
                    
        $manager->persist($jj_premieres);
        $manager->persist($jj_terminales);
        $manager->persist($jj_terminales_ancien);
        $manager->persist($mounier_montesquieu_secondes);
        $manager->flush();
        
        $this->addReference('jj_premieres', $jj_premieres);
        $this->addReference('jj_terminales', $jj_terminales);
        $this->addReference('jj_terminales_ancien', $jj_terminales_ancien);
        $this->addReference('mounier_montesquieu_secondes', $mounier_montesquieu_secondes);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 3;
    }
}
