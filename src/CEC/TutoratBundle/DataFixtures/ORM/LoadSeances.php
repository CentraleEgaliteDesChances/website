<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Seance;
use CEC\TutoratBundle\Entity\Groupe;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;


class LoadSeances extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $anneeScolaire = AnneeScolaire::withDate();
        $anneeScolaireP = new AnneeScolaire($anneeScolaire->getAnneeInferieure()-1);

        /*
        * Pour chaque groupe, on crée un nombre aléatoire entre 10 et 15 séances
        * sur l'année à partir du mois d'octobre en se basant sur les infos du groupe pour
        * le jour de séance.
        * On le fait pour l'année scolaire en cours et celle passée.
        */

        // Séances de tropajuste_comessa_secondes
            $groupe = $this->getReference('tropajuste_comessa_secondes');

            // On calcule le premier jour de séance
        $premiere_seance_P = $anneeScolaireP->getDateRentree()->add(new \DateInterval('P1M'));
        $premiere_seance = $anneeScolaire->getDateRentree()->add(new \DateInterval('P1M'));

        $premiere_seance_P->modify('first monday')->format('Y-m-d');
        $premiere_seance->modify('first monday')->format('Y-m-d');

        $seances['tropajuste_comessa_secondes_old'] = $this->seancesPourGroupe($groupe, $premiere_seance_P, rand(10,15));
        $seances['tropajuste_comessa_secondes'] = $this->seancesPourGroupe($groupe, $premiere_seance, rand(10,15));

        // Séances de tropajuste_comessa_terminales, lavy_paleuparadhi_premieres|terminales
        $groupe1 = $this->getReference('tropajuste_comessa_terminales');
        $groupe2 = $this->getReference('lavy_paleuparadhi_premieres');
        $groupe3 = $this->getReference('lavy_paleuparadhi_terminales');

            // On calcule le premier jour de séance
        $premiere_seance_P = $anneeScolaireP->getDateRentree()->add(new \DateInterval('P1M'));
        $premiere_seance = $anneeScolaire->getDateRentree()->add(new \DateInterval('P1M'));

        $premiere_seance_P->modify('first tuesday')->format('Y-m-d');
        $premiere_seance->modify('first tuesday')->format('Y-m-d');

        $seances['tropajuste_comessa_terminales_old'] = $this->seancesPourGroupe($groupe1, $premiere_seance_P, rand(10,15));
        $seances['tropajuste_comessa_terminales'] = $this->seancesPourGroupe($groupe1, $premiere_seance, rand(10,15));
        $seances['lavy_paleuparadhi_premieres_old'] = $this->seancesPourGroupe($groupe2, $premiere_seance_P, rand(10,15));
        $seances['lavy_paleuparadhi_premieres'] = $this->seancesPourGroupe($groupe2, $premiere_seance, rand(10,15));
        $seances['lavy_paleuparadhi_terminales_old'] = $this->seancesPourGroupe($groupe3, $premiere_seance_P, rand(10,15));
        $seances['lavy_paleuparadhi_terminales'] = $this->seancesPourGroupe($groupe3, $premiere_seance, rand(10,15));

        // Séances de maphore_premieres|terminales

        $groupe1 = $this->getReference('maphore_premieres');
        $groupe2 = $this->getReference('maphore_terminales');
        

            // On calcule le premier jour de séance
        $premiere_seance_P = $anneeScolaireP->getDateRentree()->add(new \DateInterval('P1M'));
        $premiere_seance = $anneeScolaire->getDateRentree()->add(new \DateInterval('P1M'));

        $premiere_seance_P->modify('first wednesday')->format('Y-m-d');
        $premiere_seance->modify('first wednesday')->format('Y-m-d');

        $seances['maphore_premieres_old'] = $this->seancesPourGroupe($groupe1, $premiere_seance_P, rand(10,15));
        $seances['maphore_premieres'] = $this->seancesPourGroupe($groupe1, $premiere_seance, rand(10,15));
        $seances['maphore_terminales_old'] = $this->seancesPourGroupe($groupe2, $premiere_seance_P, rand(10,15));
        $seances['maphore_terminales'] = $this->seancesPourGroupe($groupe2, $premiere_seance, rand(10,15));

        // Séances de tropajuste_premieres et comessa_premieres
        $groupe1 = $this->getReference('tropajuste_premieres');
        $groupe2 = $this->getReference('comessa_premieres');
        

            // On calcule le premier jour de séance
        $premiere_seance_P = $anneeScolaireP->getDateRentree()->add(new \DateInterval('P1M'));
        $premiere_seance = $anneeScolaire->getDateRentree()->add(new \DateInterval('P1M'));

        $premiere_seance_P->modify('first thursday')->format('Y-m-d');
        $premiere_seance->modify('first thursday')->format('Y-m-d');

        $seances['tropajuste_premieres_old'] = $this->seancesPourGroupe($groupe1, $premiere_seance_P, rand(10,15));
        $seances['tropajuste_premieres'] = $this->seancesPourGroupe($groupe1, $premiere_seance, rand(10,15));
        $seances['comessa_premieres_old'] = $this->seancesPourGroupe($groupe2, $premiere_seance_P, rand(10,15));
        $seances['comessa_premieres'] = $this->seancesPourGroupe($groupe2, $premiere_seance, rand(10,15));

        // Séances de palhom_kipranlamaire
        $groupe1 = $this->getReference('palhom_kipranlamaire_secondes');
        $groupe2 = $this->getReference('palhom_kipranlamaire_premieres');
        $groupe3 = $this->getReference('palhom_kipranlamaire_terminales');

            // On calcule le premier jour de séance
        $premiere_seance_P = $anneeScolaireP->getDateRentree()->add(new \DateInterval('P1M'));
        $premiere_seance = $anneeScolaire->getDateRentree()->add(new \DateInterval('P1M'));

        $premiere_seance_P->modify('first saturday')->format('Y-m-d');
        $premiere_seance->modify('first saturday')->format('Y-m-d');

        $seances['palhom_kipranlamaire_secondes_old'] = $this->seancesPourGroupe($groupe1, $premiere_seance_P, rand(10,15));
        $seances['palhom_kipranlamaire_secondes'] = $this->seancesPourGroupe($groupe1, $premiere_seance, rand(10,15));
        $seances['palhom_kipranlamaire_premieres_old'] = $this->seancesPourGroupe($groupe2, $premiere_seance_P, rand(10,15));
        $seances['palhom_kipranlamaire_premieres'] = $this->seancesPourGroupe($groupe2, $premiere_seance, rand(10,15));
        $seances['palhom_kipranlamaire_terminales_old'] = $this->seancesPourGroupe($groupe3, $premiere_seance_P, rand(10,15));
        $seances['palhom_kipranlamaire_terminales'] = $this->seancesPourGroupe($groupe3, $premiere_seance, rand(10,15));

        

        // On persiste le tout et on crée les références
        foreach ($seances as $nomGroupe => $seancesDuGroupe) {
            foreach ($seancesDuGroupe as $nomSeance => $seance) {
                $manager->persist($seance);
                $this->addReference($nomGroupe.'_'.$nomSeance, $seance);
            }
        }
        $manager->flush();
    }
    
    /**
     * Crée une nouvelle séance pour le groupe de tutorat à la date indiquée.
     * La séance est ensuite retournée par la méthode.
     *
     * @param Groupe $groupe: groupe de tutorat
     * @param \DateTime $date: date de la séance
     * @return Seance Séance de tutorat créée.
     */
    public function nouvelleSeance(Groupe $groupe, \DateTime $date)
    {
        $seance = new Seance();
        $seance->setGroupe($groupe)
               ->setDate($date);
        return $seance;
    }
    
    /**
     * Crée un tableau séances pour un groupe de tutorat donné.
     *
     * @param Groupe $groupe: groupe de tutorat
     * @param \DateTime $origineDate: origine des dates (pour la première séance)
     * @param int $nombre: nombre de séance à créer
     * @param int $intervallesEnJours: intervalle entre les séances, en jours (14 par défaut)
     * @return array Tableau des séances crées.
     */
    public function seancesPourGroupe(Groupe $groupe, \DateTime $origineDate, $nombre = 12, $intervalleEnJours = 14)
    {
        $seances = array();
        for ($i = 0; $i < $nombre; $i++) {
            $date = clone $origineDate;
            $date->add(\DateInterval::createFromDateString($intervalleEnJours * $i . ' days'));
            $seances[$i] = $this->nouvelleSeance($groupe, $date);
        }
        return $seances;
    }
    
    /**
     * Retourne un tableau contenant le nom des groupes de test.
     *
     * @return array Tableau des groupes de test.
     */
    public function tableauDesNomsGroupes()
    {
        return array(
            'tropajuste_comessa_secondes',
            'tropajuste_premieres',
            'comessa_premieres',
            'lavy_paleuparadhi_premieres',
            'lavy_paleuparadhi_terminales',
            'maphore_premieres',
            'maphore_terminales',
            'palhom_kipranlamaire_secondes',
            'palhom_kipranlamaire_premieres',
            'palhom_kipranlamaire_terminales'
        );
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\TutoratBundle\DataFixtures\ORM\LoadGroupes',
        );
    }
}
