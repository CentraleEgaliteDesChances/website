<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\GroupeEleves;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;


class LoadGroupeEleves extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
    	$anneeScolaire = AnneeScolaire::withDate();
        $anneeScolaireP= new AnneeScolaire($anneeScolaire->getAnneeInferieure()-1);

        $lyceens = array();        

        // Année passée

        $lyceens[1] = new GroupeEleves();
        $lyceens[1]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('tropajuste_comessa_secondes'))
            ->setLyceen($this->getReference('nesrine_abada'));
        $lyceens[2] = new GroupeEleves();
        $lyceens[2]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('tropajuste_premieres'))
            ->setLyceen($this->getReference('claire_alves'));
        $lyceens[3] = new GroupeEleves();
        $lyceens[3]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('comessa_premieres'))
            ->setLyceen($this->getReference('aude_ambrosini'));
        $lyceens[4] = new GroupeEleves();
        $lyceens[4]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('tropajuste_comessa_terminales'))
            ->setLyceen($this->getReference('ines_chiandotto'));
        $lyceens[5] = new GroupeEleves();
        $lyceens[5]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('lavy_paleuparadhi_premieres'))
            ->setLyceen($this->getReference('titouan_de_souza'));
        $lyceens[6] = new GroupeEleves();
        $lyceens[6]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('lavy_paleuparadhi_terminales'))
            ->setLyceen($this->getReference('leo_decoodt'));
        $lyceens[7] = new GroupeEleves();
        $lyceens[7]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('maphore_premieres'))
            ->setLyceen($this->getReference('lauren_doucet'));
        $lyceens[8] = new GroupeEleves();
        $lyceens[8]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('maphore_terminales'))
            ->setLyceen($this->getReference('maia_melina'));
        $lyceens[9] = new GroupeEleves();
        $lyceens[9]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('palhom_kipranlamaire_secondes'))
            ->setLyceen($this->getReference('mateo_sachet'));
        $lyceens[10] = new GroupeEleves();
        $lyceens[10]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('palhom_kipranlamaire_premieres'))
            ->setLyceen($this->getReference('nelson_melo'));
        $lyceens[11] = new GroupeEleves();
        $lyceens[11]->setAnneeScolaire($anneeScolaireP)
            ->setGroupe($this->getReference('palhom_kipranlamaire_terminales'))
            ->setLyceen($this->getReference('anna_michel'));

        // Année actuelle

        $lyceens[12] = new GroupeEleves();
        $lyceens[12]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('tropajuste_comessa_secondes'))
            ->setLyceen($this->getReference('mateo_sachet'));
        $lyceens[13] = new GroupeEleves();
        $lyceens[13]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('tropajuste_premieres'))
            ->setLyceen($this->getReference('nelson_melo'));
        $lyceens[14] = new GroupeEleves();
        $lyceens[14]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('comessa_premieres'))
            ->setLyceen($this->getReference('anna_michel'));
        $lyceens[15] = new GroupeEleves();
        $lyceens[15]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('tropajuste_comessa_terminales'))
            ->setLyceen($this->getReference('arnaud_milome'));
        $lyceens[16] = new GroupeEleves();
        $lyceens[16]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('lavy_paleuparadhi_premieres'))
            ->setLyceen($this->getReference('yanis_felix'));
        $lyceens[17] = new GroupeEleves();
        $lyceens[17]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('lavy_paleuparadhi_terminales'))
            ->setLyceen($this->getReference('louis_geoffroy'));
        $lyceens[18] = new GroupeEleves();
        $lyceens[18]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('maphore_premieres'))
            ->setLyceen($this->getReference('noemie_grapindor'));
        $lyceens[19] = new GroupeEleves();
        $lyceens[19]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('maphore_terminales'))
            ->setLyceen($this->getReference('emma_gausson'));
        $lyceens[20] = new GroupeEleves();
        $lyceens[20]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('palhom_kipranlamaire_secondes'))
            ->setLyceen($this->getReference('lucile_gasparini'));
        $lyceens[21] = new GroupeEleves();
        $lyceens[21]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('palhom_kipranlamaire_premieres'))
            ->setLyceen($this->getReference('mehdi_ferdoss'));
        $lyceens[22] = new GroupeEleves();
        $lyceens[22]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('palhom_kipranlamaire_terminales'))
            ->setLyceen($this->getReference('karim_el_fezzazi'));
        $lyceens[23] = new GroupeEleves();
        $lyceens[23]->setAnneeScolaire($anneeScolaire)
            ->setGroupe($this->getReference('palhom_kipranlamaire_terminales'))
            ->setLyceen($this->getReference('arno_dubois'));

        foreach($lyceens as $tutorat)
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
            'CEC\MembreBundle\DataFixtures\ORM\LoadEleves'
        );
    }
}
