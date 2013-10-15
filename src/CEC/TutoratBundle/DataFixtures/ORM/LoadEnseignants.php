<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Enseignant;

class LoadEnseignants extends AbstractFixture implements DependentFixtureInterface
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
            ->setLycee($this->getReference('tropajuste'))
            ->setTelephoneFixe('01 02 03 04 05')
            ->setTelephonePortable('0706050403')
            ->setCommentaires('Rien à dire, une fois !');
        
        $herve_ritable = new Enseignant();
        $herve_ritable->setPrenom('Hervé')
            ->setNom('Ritable')
            ->setRole("Chef d'établissement")
            ->setEmail('herve.ritable@palhom.edu.fr')
            ->setLycee($this->getReference('tropajuste'))
            ->setTelephoneFixe('01 02 03 04 05');
            
        $herve_stige = new Enseignant();
        $herve_stige->setPrenom('Hervé')
            ->setNom('Stige')
            ->setRole("Chef d'établissement")
            ->setEmail('herve.stife@ac-versailles.fr')
            ->setLycee($this->getReference('comessa'))
            ->setTelephoneFixe('01 02 03 04 05')
            ->setTelephonePortable('0706050403');
            
        $herve_jetarien = new Enseignant();
        $herve_jetarien->setPrenom('Hervé')
            ->setNom('Jétarien')
            ->setRole("Professeur référent")
            ->setEmail('herve.stife@ac-versailles.fr')
            ->setLycee($this->getReference('comessa'))
            ->setTelephoneFixe('01 02 03 04 05');
            
        $herve_thuste = new Enseignant();
        $herve_thuste->setPrenom('Hervé')
            ->setNom('Thuste')
            ->setRole("Proviseur-adjoint")
            ->setEmail('herve.thuste@ac-versailles.fr')
            ->setLycee($this->getReference('temieuhavan'));
            
        $herve_lib = new Enseignant();
        $herve_lib->setPrenom('Hervé')
            ->setNom("Lib'")
            ->setRole("Chef d'établissement")
            ->setLycee($this->getReference('lavy_paleuparadhi'))
            ->setTelephoneFixe('01 02 03 04 05')
            ->setTelephonePortable('0706050403');
            
        $herve_rolle = new Enseignant();
        $herve_rolle->setPrenom('Hervé')
            ->setNom("Rolle")
            ->setRole("Chef d'établissement")
            ->setEmail('herve.rolle@ac-versailles.fr')
            ->setLycee($this->getReference('maphore'))
            ->setTelephoneFixe('01 02 03 04 05')
            ->setTelephonePortable('0706050403');
            
        $herve_ritekifemal = new Enseignant();
        $herve_ritekifemal->setPrenom('Hervé')
            ->setNom("Ritékifémahl")
            ->setRole("Directeur de la scolarité")
            ->setEmail('h-ritekifemal@gmail.com')
            ->setLycee($this->getReference('palhom_kipranlamaire'))
            ->setTelephoneFixe('01 02 03 04 05');
            
        $herve_sikulbiliere = new Enseignant();
        $herve_sikulbiliere->setPrenom('Hervé')
            ->setNom("Sikulbilière")
            ->setRole("Proviseur")
            ->setEmail('h-ritekifemal@gmail.com')
            ->setLycee($this->getReference('palhom_kipranlamaire'));
            
        $herve_roniksansson = new Enseignant();
        $herve_roniksansson->setPrenom('Hervé')
            ->setNom("Roniksansson")
            ->setRole("Chef d'établissement")
            ->setLycee($this->getReference('heusse'))
            ->setTelephoneFixe('01 02 03 04 05');
            
        $herve_lizzy = new Enseignant();
        $herve_lizzy->setPrenom('Hervé')
            ->setNom("Lizzy")
            ->setRole("Chef d'établissement")
            ->setEmail('h-lizzy@gmail.com')
            ->setTelephoneFixe('01 02 03 04 05')
            ->setTelephonePortable('0706050403');
        
        $herve_nerable = new Enseignant();
        $herve_nerable->setPrenom('Hervé')
            ->setNom('Nérable')
            ->setRole("Chef d'établissement")
            ->setEmail('herve.nerable@palhom.edu.fr')
            ->setTelephoneFixe('01 02 03 04 05');
            
        $herve_sailenkarthon = new Enseignant();
        $herve_sailenkarthon->setPrenom('Hervé')
            ->setNom('Sailenkarthon')
            ->setRole("Chef d'établissement");
            
        $manager->persist($herve_puissetou);
        $manager->persist($herve_ritable);
        $manager->persist($herve_stige);
        $manager->persist($herve_jetarien);
        $manager->persist($herve_thuste);
        $manager->persist($herve_lib);
        $manager->persist($herve_rolle);
        $manager->persist($herve_ritekifemal);
        $manager->persist($herve_sikulbiliere);
        $manager->persist($herve_roniksansson);
        $manager->persist($herve_lizzy);
        $manager->persist($herve_nerable);
        $manager->persist($herve_sailenkarthon);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\TutoratBundle\DataFixtures\ORM\LoadLycees',
        );
    }
}
