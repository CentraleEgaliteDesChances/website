<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;


class LoadGroupes extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $aujourdhui = new \DateTime();
        $anneeScolaireActuelle = AnneeScolaire::withDate();
        $anneeScolairePrecedente = AnneeScolaire::withDate();
        $anneeScolairePrecedente->setAnneeInferieure($anneeScolairePrecedente->getAnneeInferieure() - 1);
    
        /**
         *
         * CORDEE TRUC
         *
         */
        
        $tropajuste_comessa_secondes = new Groupe();
        $tropajuste_comessa_secondes->addLycee($this->getReference('tropajuste'))
            ->addLycee($this->getReference('comessa'))
            ->setNiveau('Secondes')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-14 18:00:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setRendezVous('rendez-vous aux canapés rouges');
            
        $tropajuste_premieres = new Groupe();
        $tropajuste_premieres->addLycee($this->getReference('tropajuste'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-17 18:30:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setRendezVous("rendez-vous à l'entrée nord du bat'ens");
            
        $comessa_premieres = new Groupe();
        $comessa_premieres->addLycee($this->getReference('comessa'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-17 18:00:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setRendezVous("rendez-vous à l'entrée nord du bat'ens");
            
        $tropajuste_comessa_terminales = new Groupe();
        $tropajuste_comessa_terminales->addLycee($this->getReference('tropajuste'))
            ->addLycee($this->getReference('comessa'))
            ->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Culturel')
            ->setLieu('A Centrale')
            ->setDebut(new \DateTime('2013-10-15 17:15:00'))
            ->setFin(new \DateTime('19:15:00'))
            ->setRendezVous('rendez-vous aux canapés rouges');
        
        
        /**
         *
         * CORDEE MACHIN
         *
         */
        
        $lavy_paleuparadhi_premieres = new Groupe();
        $lavy_paleuparadhi_premieres->addLycee($this->getReference('lavy_paleuparadhi'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-15 17:30:00'))
            ->setFin(new \DateTime('19:30:00'))
            ->setRendezVous('rendez-vous aux barrières');
            
        $lavy_paleuparadhi_terminales = new Groupe();
        $lavy_paleuparadhi_terminales->addLycee($this->getReference('lavy_paleuparadhi'))
            ->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-15 17:00:00'))
            ->setFin(new \DateTime('19:00:00'))
            ->setRendezVous('rendez-vous aux barrières');
            
        $maphore_premieres = new Groupe();
        $maphore_premieres->addLycee($this->getReference('maphore'))
            ->setNiveau('Premières')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-16 18:30:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setRendezVous('rendez-vous aux barrières');
            
        $maphore_terminales = new Groupe();
        $maphore_terminales->addLycee($this->getReference('maphore'))
            ->setNiveau('Terminales')
            ->setTypeDeTutorat('Tutorat Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-16 18:30:00'))
            ->setFin(new \DateTime('20:00:00'))
            ->setRendezVous('rendez-vous aux barrières');
            
        $palhom_kipranlamaire_secondes = new Groupe();
        $palhom_kipranlamaire_secondes->addLycee($this->getReference('palhom_kipranlamaire'))
            ->setNiveau('Secondes')
            ->setTypeDeTutorat('Tutorat Culturel et Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-19 9:00:00'))
            ->setFin(new \DateTime('12:00:00'))
            ->setRendezVous('rendez-vous sur le parking');
            
        $palhom_kipranlamaire_premieres_terminales = new Groupe();
        $palhom_kipranlamaire_premieres_terminales->addLycee($this->getReference('palhom_kipranlamaire'))
            ->setNiveau('Premières et Terminales')
            ->setTypeDeTutorat('Tutorat Culturel et Scientifique')
            ->setLieu('Dans le lycée')
            ->setDebut(new \DateTime('2013-10-19 9:00:00'))
            ->setFin(new \DateTime('12:00:00'))
            ->setRendezVous('rendez-vous sur le parking');
        
        // On crée un tableau des groupes de tutorat
        $groupes = array();
        $groupes['tropajuste_comessa_secondes'] = $tropajuste_comessa_secondes;
        $groupes['tropajuste_premieres'] = $tropajuste_premieres;
        $groupes['comessa_premieres'] = $comessa_premieres;
        $groupes['lavy_paleuparadhi_premieres'] = $lavy_paleuparadhi_premieres;
        $groupes['lavy_paleuparadhi_terminales'] = $lavy_paleuparadhi_terminales;
        $groupes['maphore_premieres'] = $maphore_premieres;
        $groupes['maphore_terminales'] = $maphore_terminales;
        $groupes['palhom_kipranlamaire_secondes'] = $palhom_kipranlamaire_secondes;
        $groupes['palhom_kipranlamaire_premieres_terminales'] = $palhom_kipranlamaire_premieres_terminales;
        
        // On persiste tous les groupes et on ajoute les références
        foreach ($groupes as $nomGroupe => $groupe) {
            $manager->persist($groupe);
            $this->addReference($nomGroupe, $groupe);
        }
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
