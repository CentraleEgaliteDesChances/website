<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Enseignant;

class ChargeEnseignants extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $guillou = new Enseignant();
        $guillou->setPrenom('Philippe')
            ->setNom('Le Guillou')
            ->setEmail('phi.le.guillou@gmail.com');
        
        $merlet = new Enseignant();
        $merlet->setPrenom('Thierry')
            ->setNom('Merlet')
            ->setEmail('thi.merlet@gmail.com');
        
        $laine = new Enseignant();
        $laine->setPrenom('Bernadette')
            ->setNom('Lainé')
            ->setEmail('b_laine_huppe@hotmail.com')
            ->setTelephonePortable('06 66 20 43 48 ')
            ->setTelephoneFixe('01 48 49 35 69');
            
        $manager->persist($guillou);
        $manager->persist($merlet);
        $manager->persist($laine);
        $manager->flush();
        
        $this->addReference('guillou', $guillou);
        $this->addReference('merlet', $merlet);
        $this->addReference('laine', $laine);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1;
    }
}
