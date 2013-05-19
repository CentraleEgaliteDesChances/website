<?php

namespace CEC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\ActiviteBundle\Entity\CompteRendu;

class LoadCompteRendus extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $maintenant = new \DateTime();
    
        $crs1 = new CompteRendu();
        $crs1->setActivite($this->getReference('acti1'))
             ->setNoteContenu(4)
             ->setNoteInteractivite(3)
             ->setNoteAtteinteObjectifs(4)
             ->setDureeAdaptee(0)
             ->setAuteur($this->getReference('pol_maire'))
             ->setDateCreation($maintenant)
             ->setDateModification($maintenant);
             
        $crs2 = new CompteRendu();
        $crs2->setActivite($this->getReference('acti1'))
             ->setNoteContenu(4)
             ->setNoteInteractivite(2)
             ->setNoteAtteinteObjectifs(3)
             ->setDureeAdaptee(1)
             ->setAuteur($this->getReference('helene_sicsic'))
             ->setDateCreation($maintenant)
             ->setDateModification($maintenant);
             
        $crs3 = new CompteRendu();
        $crs3->setActivite($this->getReference('acti1'))
             ->setNoteContenu(3)
             ->setNoteInteractivite(4)
             ->setNoteAtteinteObjectifs(5)
             ->setDureeAdaptee(1)
             ->setAuteur($this->getReference('helene_sicsic'))
             ->setDateCreation($maintenant)
             ->setDateModification($maintenant);
        
        $manager->persist($crs1);
        $manager->persist($crs2);
        $manager->persist($crs3);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 80;
    }
}
