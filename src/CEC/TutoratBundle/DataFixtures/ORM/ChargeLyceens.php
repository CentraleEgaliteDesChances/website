<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Lyceen;


class ChargeLyceen extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $medhi_amalou = new Lyceen();
        $medhi_amalou->setPrenom('Mehdi')
            ->setNom('Amalou')
            ->setTelephone('06 23 67 61 28')
            ->setEmail('mehdi.amalou@gmail.com')
            ->setAdresse('92, rue de Paris')
            ->setCodePostal('93100')
            ->setVille('Montreuil')
            ->setNomPere('Marcel Amadou')
            ->setNomMere('Sarah Claire')
            ->setTelephoneParent('0203040302');
            
        $meissa_dieng = new Lyceen();
        $meissa_dieng->setPrenom('Meïssa')
            ->setNom('Dieng')
            ->setTelephone('06 16 18 74 22')
            ->setEmail('meissa.dieng18@gmail.com')
            ->setAdresse('12, rue Hector Guimard')
            ->setCodePostal('75019')
            ->setVille('Paris')
            ->setNomPere('Marcel Dieng')
            ->setNomMere('Sarah Claire')
            ->setTelephoneParent('06 64 66 57 25');
            
        $tara_delpech = new Lyceen();
        $tara_delpech->setPrenom('Delpech')
            ->setNom('Tara')
            ->setTelephone('06 70 77 34 86')
            ->setEmail('tara.delpech@hotmail.fr')
            ->setAdresse('20 rue Lucien Sampaix')
            ->setCodePostal('75010')
            ->setVille('Paris')
            ->setNomPere('Marcel Delpech')
            ->setNomMere('Sarah Claire')
            ->setTelephoneParent('06 70 79 96 15');
            
        $pauline_tampier = new Lyceen();
        $pauline_tampier->setPrenom('Pauline')
            ->setNom('Tampier')
            ->setTelephone('06 26 25 84 22')
            ->setEmail('p.tampier@laposte.net')
            ->setAdresse('84 rue du Chemin Vert')
            ->setCodePostal('75011')
            ->setVille('Paris')
            ->setNomPere('Marcel Tampier')
            ->setNomMere('Sarah Claire')
            ->setTelephoneParent('06 12 16 29 20');
            
        $manager->persist($medhi_amalou);
        $manager->persist($meissa_dieng);
        $manager->persist($tara_delpech);
        $manager->persist($pauline_tampier);
        $manager->flush();
        
        $this->addReference('medhi_amalou', $medhi_amalou);
        $this->addReference('meissa_dieng', $meissa_dieng);
        $this->addReference('tara_delpech', $tara_delpech);
        $this->addReference('pauline_tampier', $pauline_tampier);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1;
    }
}
