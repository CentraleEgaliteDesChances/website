<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Lycee;

class ChargeLycees extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $jj = new Lycee();
        $jj->setNom('Lycée Jean Jaurès')
            ->setAdresse('1, Rue Dombasle')
            ->setCodePostal('93100')
            ->setVille('Montreuil-sous-Bois')
            ->setStatut('Établissement Public')
            ->setTelephone('01 42 87 49 84')
            ->setPivot(false)
            ->setZEP(true);
        
        $mounier = new Lycee();
        $mounier->setNom('Lycée Emmanuel Mounier')
            ->setAdresse('35, Rue des Près Hauts')
            ->setCodePostal('92290')
            ->setVille('Châtenay-Malabry')
            ->setStatut('Établissement Public')
            ->setTelephone('01 41 87 60 30')
            ->setPivot(false)
            ->setZEP(false);
        
        $montesquieu = new Lycee();
        $montesquieu->setNom('Lycée Montesquieu')
            ->setAdresse('23, Rue du Capitaine Facqs')
            ->setCodePostal('92350')
            ->setVille('Le Plessis-Robinson')
            ->setStatut('Établissement Public')
            ->setTelephone('01 46 30 35 61')
            ->setPivot(false)
            ->setZEP(false);
            
        $vilgenis = new Lycee();
        $vilgenis->setNom('Lycée Parc de Vilgénis')
            ->setAdresse('80, Rue de Versailles')
            ->setCodePostal('91305')
            ->setVille('Massy')
            ->setStatut('Établissement Public')
            ->setTelephone('01 69 53 74 00')
            ->setPivot(false)
            ->setZEP(false);
            
        $ginette = new Lycee();
        $ginette->setNom('Lycée Sainte-Geneviève')
            ->setAdresse('80, Rue de Versailles')
            ->setCodePostal('91305')
            ->setVille('Massy')
            ->setStatut('Établissement Public')
            ->setTelephone('01 69 53 74 00')
            ->setPivot(true)
            ->setZEP(false);
            
        $manager->persist($jj);
        $manager->persist($mounier);
        $manager->persist($montesquieu);
        $manager->persist($vilgenis);
        $manager->persist($ginette);
        $manager->flush();
        
        $this->addReference('jj', $jj);
        $this->addReference('mounier', $mounier);
        $this->addReference('montesquieu', $montesquieu);
        $this->addReference('vilgenis', $vilgenis);
        $this->addReference('ginette', $ginette);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }
}
