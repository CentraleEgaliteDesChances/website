<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Seance;


class LoadSeances extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $hier = new \DateTime();
        $hier->sub(\DateInterval::createFromDateString('1 day'));
        $seance1 = new Seance();
        $seance1->setGroupe($this->getReference('palhom_kipranlamaire_secondes'))
            ->setDate($hier);
        
        $dans7Jours = new \DateTime();
        $dans7Jours->add(\DateInterval::createFromDateString('7 days'));
        $seance2 = new Seance();
        $seance2->setGroupe($this->getReference('palhom_kipranlamaire_premieres_terminales'))
            ->setDate($dans7Jours);
            
        $dans3Jours = new \DateTime();
        $dans3Jours->add(\DateInterval::createFromDateString('3 days'));
        $seance3 = new Seance();
        $seance3->setGroupe($this->getReference('maphore_terminales'))
            ->setDate($dans3Jours)
            ->setDebut(new \DateTime('18:30:00'))
            ->setFin(new \DateTime('21:00:00'));
                
        $seance4 = new Seance();
        $seance4->setGroupe($this->getReference('maphore_premieres'))
            ->setDate($dans3Jours)
            ->setDebut(new \DateTime('17:00:00'))
            ->setFin(new \DateTime('19:30:00'));
                
        $seance5 = new Seance();
        $seance5->setGroupe($this->getReference('lavy_paleuparadhi_terminales'))
            ->setDate(new \DateTime('2013-04-27 17:30:00'));
        
        $manager->persist($seance1);
        $manager->persist($seance2);
        $manager->persist($seance3);
        $manager->persist($seance4);
        $manager->persist($seance5);
        $manager->flush();
        
        $this->addReference('seance1', $seance1);
        $this->addReference('seance2', $seance2);
        $this->addReference('seance3', $seance3);
        $this->addReference('seance4', $seance4);
        $this->addReference('seance5', $seance5);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 40;
    }
}
