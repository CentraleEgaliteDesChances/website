<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Enseignant;

class LoadEnseignants extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $guillou = new Enseignant();
        $guillou->setPrenom('Philippe')
            ->setNom('Le Guillou')
            ->setRole('Chef d\'établissement')
            ->setEmail('phi.le.guillou@gmail.com')
            ->setLycee($this->getReference('jj'));
        
        $merlet = new Enseignant();
        $merlet->setPrenom('Thierry')
            ->setNom('Merlet')
            ->setRole('Proviseur-adjoint')
            ->setEmail('thi.merlet@gmail.com');
        
        $laine = new Enseignant();
        $laine->setPrenom('Bernadette')
            ->setNom('Lainé')
            ->setRole('Professeur référent')
            ->setEmail('b_laine_huppe@hotmail.com')
            ->setTelephonePortable('06 66 20 43 48 ')
            ->setTelephoneFixe('01 48 49 35 69')
            ->setLycee($this->getReference('jj'));
            
        $manager->persist($guillou);
        $manager->persist($merlet);
        $manager->persist($laine);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 3;
    }
}
