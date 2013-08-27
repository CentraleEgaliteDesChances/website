<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Seance;
use CEC\TutoratBundle\Entity\Groupe;


class LoadSeances extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Tableau contenant tous les groupes de test
        $groupes = $this->tableauDesNomsGroupes();
        
        // On crée les dates de base
        $anneePrecedante = new \DateTime(
            $this->getReference('tropajuste_comessa_secondes_old')
                 ->getAnneeScolaire()
                 ->getAnneeInferieure()
            . '-10-01'
        );
        
        $anneeActuelle = new \DateTime(
            $this->getReference('tropajuste_comessa_secondes')
                 ->getAnneeScolaire()
                 ->getAnneeInferieure()
            . '-10-01'
        );
        
        // On crée le tableau contenant toutes les séances
        $seances = array();
        foreach ($groupes as $nomGroupe) {
            $groupe = $this->getReference($nomGroupe);
            $dateOrigine = (substr($nomGroupe, strlen($nomGroupe) - 3) == 'old') ? $anneePrecedante : $anneeActuelle;
            $decalageOrigine = rand(1, 20);
            $dateOrigine->add(\DateInterval::createFromDateString($decalageOrigine . ' days'));
            $seances[$nomGroupe] = $this->seancesPourGroupe(
                $this->getReference('tropajuste_comessa_secondes_old'),
                $dateOrigine,
                rand(13, 20)
            );
        }
        
        ///// TODO: Ajouter des séances dont les infos diffèrent du groupe

        // On persiste le tout et on crée les références
        foreach ($seances as $nomGroupe => $seancesDuGroupe) {
            foreach ($seancesDuGroupe as $nomSeance => $seance) {
                $manager->persist($seance);
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
     * @param int $intervallesEnJour: intervalle entre les séances, en jours (14 par défaut)
     * @return array Tableau des séances crées.
     */
    public function seancesPourGroupe(Groupe $groupe, \DateTime $origineDate, $nombre = 12, $intervalleEnJour = 14)
    {
        $seances = array();
        for ($i = 0; $i < $nombre; $i++) {
            $date = clone $origineDate;
            $date->add(\DateInterval::createFromDateString($intervalleEnJour * $i . ' days'));
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
            'tropajuste_comessa_secondes_old',
            'tropajuste_premieres_old',
            'comessa_premieres_old',
            'tropajuste_comessa_terminales_old',
            'tropajuste_comessa_secondes',
            'tropajuste_premieres',
            'comessa_premieres',
            'lavy_paleuparadhi_premieres_old',
            'lavy_paleuparadhi_terminales_old',
            'maphore_premieres_old',
            'maphore_terminales_old',
            'palhom_kipranlamaire_secondes_old',
            'palhom_kipranlamaire_premieres_terminales_old',
            'lavy_paleuparadhi_premieres',
            'lavy_paleuparadhi_terminales',
            'maphore_premieres',
            'maphore_terminales',
            'palhom_kipranlamaire_secondes',
            'palhom_kipranlamaire_premieres_terminales',
        );
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 40;
    }
}
