<?php

namespace CEC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\ActiviteBundle\Entity\Document;

class LoadDocuments extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // File-fixtures paths
        $pdfFixture = "data-fixture.pdf";
        $wordFixture = "data-fixture.doc";
        
        $maintenant = new \DateTime();
    
        $acti1v1 = new Document();
        $acti1v1->setNomFichierPDF($pdfFixture)
                ->setNomFichierWord($wordFixture)
                ->setDateCreation($maintenant)
                ->setDateModification($maintenant)
                ->setAuteur($this->getReference('pol_maire'))
                ->setActivite($this->getReference('acti1'));
        
        $acti1v2 = new Document();
        $acti1v2->setNomFichierPDF($pdfFixture)
                ->setNomFichierWord($wordFixture)
                ->setDateCreation($maintenant)
                ->setDateModification($maintenant)
                ->setAuteur($this->getReference('helene_sicsic'))
                ->setActivite($this->getReference('acti1'));
                
        $acti2v1 = new Document();
        $acti2v1->setNomFichierPDF($pdfFixture)
              ->setNomFichierWord($wordFixture)
              ->setDateCreation($maintenant)
              ->setDateModification($maintenant)
              ->setAuteur($this->getReference('pol_maire'))
              ->setActivite($this->getReference('acti2'));
        
        $manager->persist($acti1v1);
        $manager->persist($acti1v2);
        $manager->persist($acti2v1);
        $manager->flush();
        
        $this->addReference('acti1v1', $acti1v1);
        $this->addReference('acti1v2', $acti1v2);
        $this->addReference('acti2v1', $acti2v1);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 80;
    }
}
