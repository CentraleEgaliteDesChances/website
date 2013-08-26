<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Lycee;

class LoadLycees extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tropajuste = new Lycee();
        $tropajuste->setNom('Lycée Tropajust')
            ->setAdresse('5, Avenue Caliméro')
            ->setCodePostal('92310')
            ->setVille('Malhémé')
            ->setStatut('Établissement Public')
            ->setTelephone('0123456789')
            ->setPivot(false)
            ->setZEP(true)
            ->setCordee($this->getReference('truc'));
            
        $comessa = new Lycee();
        $comessa->setNom('Lycée Comessa')
            ->setAdresse('45, rue des Fatalistes')
            ->setCodePostal('23890')
            ->setVille("Daix sous l'On")
            ->setStatut('Établissement Privé')
            ->setTelephone('0123456789')
            ->setPivot(false)
            ->setZEP(false)
            ->setCordee($this->getReference('truc'));
        
        $temieuhavan = new Lycee();
        $temieuhavan->setNom('Lycée Témieuhavan')
            ->setAdresse('99, Impasse de la Nostalgie')
            ->setCodePostal('78540')
            ->setVille('Vieux-Conville')
            ->setStatut('Établissement Public')
            ->setTelephone('0123456789')
            ->setPivot(true)
            ->setZEP(false)
            ->setCordee($this->getReference('truc'));
            
        $lavy_paleuparadhi = new Lycee();
        $lavy_paleuparadhi->setNom('Lycée Lavy Paleuparadhi')
            ->setAdresse('5, Sentier Zazie')
            ->setCodePostal('92310')
            ->setVille('Chansshon')
            ->setStatut('Établissement Privé')
            ->setTelephone('0123456789')
            ->setPivot(false)
            ->setZEP(false)
            ->setCordee($this->getReference('machin'));
            
        $maphore = new Lycee();
        $maphore->setNom('Lycée Maphore')
            ->setAdresse('5, boulevard des Phares et des Feux')
            ->setCodePostal('75013')
            ->setVille('Tricolor City')
            ->setStatut('Établissement Public')
            ->setTelephone('0123456789')
            ->setPivot(false)
            ->setZEP(false)
            ->setCordee($this->getReference('machin'));
            
        $palhom_kipranlamaire = new Lycee();
        $palhom_kipranlamaire->setNom('Lycée Palhom Kipranlamaire')
            ->setAdresse('23, Route du Port')
            ->setCodePostal('92310')
            ->setVille('Babor-Tribhor')
            ->setStatut('Établissement Public')
            ->setTelephone('0123456789')
            ->setPivot(false)
            ->setZEP(true)
            ->setCordee($this->getReference('machin'));
            
        $heusse = new Lycee();
        $heusse->setNom('Lycée Heussé')
            ->setAdresse('7 bis, montée des Associations')
            ->setCodePostal('92280')
            ->setVille('Châlenay-Matabry')
            ->setStatut('Établissement Privé')
            ->setTelephone('0123456789')
            ->setPivot(true)
            ->setZEP(false)
            ->setCordee($this->getReference('machin'));
            
        $manager->persist($tropajuste);
        $manager->persist($comessa);
        $manager->persist($temieuhavan);
        $manager->persist($lavy_paleuparadhi);
        $manager->persist($maphore);
        $manager->persist($palhom_kipranlamaire);
        $manager->persist($heusse);
        $manager->flush();
        
        $this->addReference('tropajuste', $tropajuste);
        $this->addReference('comessa', $comessa);
        $this->addReference('temieuhavan', $temieuhavan);
        $this->addReference('lavy_paleuparadhi', $lavy_paleuparadhi);
        $this->addReference('maphore', $maphore);
        $this->addReference('palhom_kipranlamaire', $palhom_kipranlamaire);
        $this->addReference('heusse', $heusse);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 20;
    }
}
