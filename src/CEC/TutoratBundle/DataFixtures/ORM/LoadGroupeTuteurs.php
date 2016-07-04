<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\GroupeTuteurs;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;


class LoadGroupeTuteurs extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $anneeScolaire = AnneeScolaire::withDate();
        $anneeScolaireP= new AnneeScolaire($anneeScolaire->getAnneeInferieure()-1);

        $tuteurs = array();        

        // Année passée

        $tuteurs[1] = new GroupeTuteurs();
        $tuteurs[1]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('tropajuste_comessa_secondes'))
            ->setTuteur($this->getReference('pol_maire'));
        $tuteurs[2] = new GroupeTuteurs();
        $tuteurs[2]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('tropajuste_premieres'))
            ->setTuteur($this->getReference('helene_sicsic'));
        $tuteurs[3] = new GroupeTuteurs();
        $tuteurs[3]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('comessa_premieres'))
            ->setTuteur($this->getReference('jimmy_eung'));
        $tuteurs[4] = new GroupeTuteurs();
        $tuteurs[4]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('tropajuste_comessa_terminales'))
            ->setTuteur($this->getReference('jb_bayle'));
        $tuteurs[5] = new GroupeTuteurs();
        $tuteurs[5]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('lavy_paleuparadhi_premieres'))
            ->setTuteur($this->getReference('eloise_vailland'));
        $tuteurs[6] = new GroupeTuteurs();
        $tuteurs[6]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('lavy_paleuparadhi_terminales'))
            ->setTuteur($this->getReference('charles_giachetti'));
        $tuteurs[7] = new GroupeTuteurs();
        $tuteurs[7]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('maphore_premieres'))
            ->setTuteur($this->getReference('paul_chauchat'));
        $tuteurs[8] = new GroupeTuteurs();
        $tuteurs[8]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('maphore_terminales'))
            ->setTuteur($this->getReference('ml_charpignon'));
        $tuteurs[9] = new GroupeTuteurs();
        $tuteurs[9]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('palhom_kipranlamaire_secondes'))
            ->setTuteur($this->getReference('thomas_beligne'));
        $tuteurs[10] = new GroupeTuteurs();
        $tuteurs[10]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('palhom_kipranlamaire_premieres'))
            ->setTuteur($this->getReference('gurvan_hermange'));
        $tuteurs[11] = new GroupeTuteurs();
        $tuteurs[11]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('palhom_kipranlamaire_terminales'))
            ->setTuteur($this->getReference('jean_philippe_de_la_taillardiere'));

        // Année actuelle

        $tuteurs[12] = new GroupeTuteurs();
        $tuteurs[12]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('tropajuste_comessa_secondes'))
            ->setTuteur($this->getReference('pol_maire'));
        $tuteurs[13] = new GroupeTuteurs();
        $tuteurs[13]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('tropajuste_premieres'))
            ->setTuteur($this->getReference('helene_sicsic'));
        $tuteurs[14] = new GroupeTuteurs();
        $tuteurs[14]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('comessa_premieres'))
            ->setTuteur($this->getReference('jimmy_eung'));
        $tuteurs[15] = new GroupeTuteurs();
        $tuteurs[15]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('tropajuste_comessa_terminales'))
            ->setTuteur($this->getReference('jb_bayle'));
        $tuteurs[16] = new GroupeTuteurs();
        $tuteurs[16]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('lavy_paleuparadhi_premieres'))
            ->setTuteur($this->getReference('eloise_vailland'));
        $tuteurs[17] = new GroupeTuteurs();
        $tuteurs[17]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('lavy_paleuparadhi_terminales'))
            ->setTuteur($this->getReference('charles_giachetti'));
        $tuteurs[18] = new GroupeTuteurs();
        $tuteurs[18]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('maphore_premieres'))
            ->setTuteur($this->getReference('paul_chauchat'));
        $tuteurs[19] = new GroupeTuteurs();
        $tuteurs[19]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('maphore_terminales'))
            ->setTuteur($this->getReference('ml_charpignon'));
        $tuteurs[20] = new GroupeTuteurs();
        $tuteurs[20]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('palhom_kipranlamaire_secondes'))
            ->setTuteur($this->getReference('thomas_beligne'));
        $tuteurs[21] = new GroupeTuteurs();
        $tuteurs[21]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('palhom_kipranlamaire_premieres'))
            ->setTuteur($this->getReference('gurvan_hermange'));
        $tuteurs[22] = new GroupeTuteurs();
        $tuteurs[22]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('palhom_kipranlamaire_terminales'))
            ->setTuteur($this->getReference('jean_philippe_de_la_taillardiere'));
        $tuteurs[23] = new GroupeTuteurs();
        $tuteurs[23]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('palhom_kipranlamaire_terminales'))
            ->setTuteur($this->getReference('tristan_pouliquen'));


        foreach($tuteurs as $tutorat)
        {
            $manager->persist($tutorat);
        }

        $manager->flush();

    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\TutoratBundle\DataFixtures\ORM\LoadGroupes',
            'CEC\MembreBundle\DataFixtures\ORM\LoadMembres'
        );
    }
}
