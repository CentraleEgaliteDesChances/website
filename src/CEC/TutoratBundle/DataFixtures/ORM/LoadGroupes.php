<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\MainBundle\Utility\AnneeScolaire;


class LoadGroupes extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $aujourdhui = new \DateTime();
        $anneeScolaireActuelle = new AnneeScolaire($aujourdhui->format('Y'));
        $anneeScolairePrecedente = new AnneeScolaire($aujourdhui->format('Y') - 1);
    
        /**
         *
         * CORDEE TRUC
         *
         */
        
        // Année précédente
        $tropajuste_comessa_secondes_old = new Groupe();
        $tropajuste_comessa_secondes_old->addLycee($this->getReference('tropajuste'))
            ->addLycee($this->getReference('comessa'))
            ->setNiveau('Secondes')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-14 18:00:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous('rendez-vous aux canapés rouges');
            
        $tropajuste_premieres_old = new Groupe();
        $tropajuste_premieres_old->addLycee($this->getReference('tropajuste'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-17 18:30:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous('rendez-vous aux canapés rouges');
            
        $comessa_premieres_old = new Groupe();
        $comessa_premieres_old->addLycee($this->getReference('comessa'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-17 18:00:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous("rendez-vous à l'entrée nord du bat'ens");
            
        $tropajuste_comessa_terminales_old = new Groupe();
        $tropajuste_comessa_terminales_old->addLycee($this->getReference('tropajuste'))
            ->addLycee($this->getReference('comessa'))
            ->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-15 17:15:00'))
            ->setFin(new \DateTime('19:15:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous('rendez-vous aux canapés rouges');
            
        // Année actuelle
        $tropajuste_comessa_secondes = new Groupe();
        $tropajuste_comessa_secondes->addLycee($this->getReference('tropajuste'))
            ->addLycee($this->getReference('comessa'))
            ->setNiveau('Secondes')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-14 18:00:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous('rendez-vous aux canapés rouges');
            
        $tropajuste_premieres = new Groupe();
        $tropajuste_premieres->addLycee($this->getReference('tropajuste'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-17 18:30:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous("rendez-vous à l'entrée nord du bat'ens");
            
        $comessa_premieres = new Groupe();
        $comessa_premieres->addLycee($this->getReference('comessa'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-17 18:00:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous("rendez-vous à l'entrée nord du bat'ens");
            
        $tropajuste_comessa_terminales = new Groupe();
        $tropajuste_comessa_terminales->addLycee($this->getReference('tropajuste'))
            ->addLycee($this->getReference('comessa'))
            ->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-15 17:15:00'))
            ->setFin(new \DateTime('19:15:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous('rendez-vous aux canapés rouges');
        
        
        /**
         *
         * CORDEE MACHIN
         *
         */
        
        // Année précédente
        $lavy_paleuparadhi_premieres_old = new Groupe();
        $lavy_paleuparadhi_premieres_old->addLycee($this->getReference('lavy_paleuparadhi'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-15 17:00:00'))
            ->setFin(new \DateTime('19:00:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous('rendez-vous aux barrières');
            
        $lavy_paleuparadhi_terminales_old = new Groupe();
        $lavy_paleuparadhi_terminales_old->addLycee($this->getReference('lavy_paleuparadhi'))
            ->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-15 17:00:00'))
            ->setFin(new \DateTime('19:00:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous('rendez-vous aux barrières');
            
        $maphore_premieres_old = new Groupe();
        $maphore_premieres_old->addLycee($this->getReference('maphore'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-16 18:30:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous('rendez-vous aux barrières');
            
        $maphore_terminales_old = new Groupe();
        $maphore_terminales_old->addLycee($this->getReference('maphore'))
            ->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-16 18:30:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous('rendez-vous aux barrières');
            
        $palhom_kipranlamaire_secondes_old = new Groupe();
        $palhom_kipranlamaire_secondes_old->addLycee($this->getReference('palhom_kipranlamaire'))
            ->setNiveau('Secondes')
            ->setTypeDeTutorat('Tutorat Culturel et Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-19 9:00:00'))
            ->setFin(new \DateTime('12:00:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous('rendez-vous sur le parking');
            
        $palhom_kipranlamaire_premieres_terminales_old = new Groupe();
        $palhom_kipranlamaire_premieres_terminales_old->addLycee($this->getReference('palhom_kipranlamaire'))
            ->setNiveau('Premières et Terminales')
            ->setTypeDeTutorat('Tutorat Culturel et Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-19 9:00:00'))
            ->setFin(new \DateTime('12:00:00'))
            ->setAnneeScolaire($anneeScolairePrecedente)
            ->setRendezVous('rendez-vous sur le parking');
            
        // Année actuelle
        $lavy_paleuparadhi_premieres = new Groupe();
        $lavy_paleuparadhi_premieres->addLycee($this->getReference('lavy_paleuparadhi'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-15 17:30:00'))
            ->setFin(new \DateTime('19:30:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous('rendez-vous aux barrières');
            
        $lavy_paleuparadhi_terminales = new Groupe();
        $lavy_paleuparadhi_terminales->addLycee($this->getReference('lavy_paleuparadhi'))
            ->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-15 17:00:00'))
            ->setFin(new \DateTime('19:00:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous('rendez-vous aux barrières');
            
        $maphore_premieres = new Groupe();
        $maphore_premieres->addLycee($this->getReference('maphore'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-16 18:30:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous('rendez-vous aux barrières');
            
        $maphore_terminales = new Groupe();
        $maphore_terminales->addLycee($this->getReference('maphore'))
            ->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-16 18:30:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous('rendez-vous aux barrières');
            
        $palhom_kipranlamaire_secondes = new Groupe();
        $palhom_kipranlamaire_secondes->addLycee($this->getReference('palhom_kipranlamaire'))
            ->setNiveau('Secondes')
            ->setTypeDeTutorat('Tutorat Culturel et Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-19 9:00:00'))
            ->setFin(new \DateTime('12:00:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous('rendez-vous sur le parking');
            
        $palhom_kipranlamaire_premieres_terminales = new Groupe();
        $palhom_kipranlamaire_premieres_terminales->addLycee($this->getReference('palhom_kipranlamaire'))
            ->setNiveau('Premières et Terminales')
            ->setTypeDeTutorat('Tutorat Culturel et Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-19 9:00:00'))
            ->setFin(new \DateTime('12:00:00'))
            ->setAnneeScolaire($anneeScolaireActuelle)
            ->setRendezVous('rendez-vous sur le parking');
        
                    
        $manager->persist($tropajuste_comessa_secondes_old);
        $manager->persist($tropajuste_premieres_old);
        $manager->persist($comessa_premieres_old);
        $manager->persist($tropajuste_comessa_terminales_old);
        $manager->persist($tropajuste_comessa_secondes);
        $manager->persist($tropajuste_premieres);
        $manager->persist($comessa_premieres);
        $manager->persist($tropajuste_comessa_terminales);
        $manager->persist($lavy_paleuparadhi_premieres_old);
        $manager->persist($lavy_paleuparadhi_terminales_old);
        $manager->persist($maphore_premieres_old);
        $manager->persist($maphore_terminales_old);
        $manager->persist($palhom_kipranlamaire_secondes_old);
        $manager->persist($palhom_kipranlamaire_premieres_terminales_old);
        $manager->persist($lavy_paleuparadhi_premieres);
        $manager->persist($lavy_paleuparadhi_terminales);
        $manager->persist($maphore_premieres);
        $manager->persist($maphore_terminales);
        $manager->persist($palhom_kipranlamaire_secondes);
        $manager->persist($palhom_kipranlamaire_premieres_terminales);
        $manager->flush();
        
        $this->addReference('tropajuste_comessa_secondes_old', $tropajuste_comessa_secondes_old);
        $this->addReference('tropajuste_premieres_old', $tropajuste_premieres_old);
        $this->addReference('comessa_premieres_old', $comessa_premieres_old);
        $this->addReference('tropajuste_comessa_terminales_old', $tropajuste_comessa_terminales_old);
        $this->addReference('tropajuste_comessa_secondes', $tropajuste_comessa_secondes);
        $this->addReference('tropajuste_premieres', $tropajuste_premieres);
        $this->addReference('comessa_premieres', $comessa_premieres);
        $this->addReference('tropajuste_comessa_terminales', $tropajuste_comessa_terminales);
        $this->addReference('lavy_paleuparadhi_premieres_old', $lavy_paleuparadhi_premieres_old);
        $this->addReference('lavy_paleuparadhi_terminales_old', $lavy_paleuparadhi_terminales_old);
        $this->addReference('maphore_premieres_old', $maphore_premieres_old);
        $this->addReference('maphore_terminales_old', $maphore_terminales_old);
        $this->addReference('palhom_kipranlamaire_secondes_old', $palhom_kipranlamaire_secondes_old);
        $this->addReference('palhom_kipranlamaire_premieres_terminales_old', $palhom_kipranlamaire_premieres_terminales_old);
        $this->addReference('lavy_paleuparadhi_premieres', $lavy_paleuparadhi_premieres);
        $this->addReference('lavy_paleuparadhi_terminales', $lavy_paleuparadhi_terminales);
        $this->addReference('maphore_premieres', $maphore_premieres);
        $this->addReference('maphore_terminales', $maphore_terminales);
        $this->addReference('palhom_kipranlamaire_secondes', $palhom_kipranlamaire_secondes);
        $this->addReference('palhom_kipranlamaire_premieres_terminales', $palhom_kipranlamaire_premieres_terminales);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 30;
    }
}
