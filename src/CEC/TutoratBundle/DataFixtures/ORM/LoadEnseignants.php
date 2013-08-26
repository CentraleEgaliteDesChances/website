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
        $herve_puissetou = new Enseignant();
        $herve_puissetou->setPrenom('Hervé')
            ->setNom('Puissétou')
            ->setRole('Professeur de littérature belge')
            ->setEmail('herve.leon@debruxelle.be')
            ->setLycee($this->getReference('palhom_kipranlamaire'))
            ->setTelephoneFixe('01 02 03 04 05')
            ->setTelephonePortable('0706050403')
            ->setCommentaires('Rien à dire, une fois !');
        
        $herve_ritable = new Enseignant();
        $herve_ritable->setPrenom('Hervé')
            ->setNom('Ritable')
            ->setRole("Chef d'établissement")
            ->setEmail('herve.ritable@palhom.edu.fr')
            ->setLycee($this->getReference('palhom_kipranlamaire'))
            ->setTelephoneFixe('01 02 03 04 05')
            ->setTelephonePortable('0706050403');
        
        $herve_nerable = new Enseignant();
        $herve_nerable->setPrenom('Hervé')
            ->setNom('Nérable')
            ->setRole("Chef d'établissement")
            ->setEmail('herve.nerable@palhom.edu.fr')
            ->setTelephoneFixe('01 02 03 04 05')
            ->setTelephonePortable('0706050403');
            
        $manager->persist($herve_puissetou);
        $manager->persist($herve_ritable);
        $manager->persist($herve_nerable);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 30;
    }
}
