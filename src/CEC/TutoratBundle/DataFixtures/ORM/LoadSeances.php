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
        //Par defaut elles commencent toutes le premier janvier
        $annee = date('Y');
        $dateBase = new \DateTime($annee.'-01-01');
        
        // On crée le tableau contenant toutes les séances
        $seances = array();
        $groupes = $this->tableauDesNomsGroupes();
        foreach ($groupes as $nomGroupe) {
            $groupe = $this->getReference($nomGroupe);
            $dateOrigine = $dateBase;
            $decalageOrigine = rand(1, 20);
            $dateOrigine->add(\DateInterval::createFromDateString($decalageOrigine . ' days'));
            $seances[$nomGroupe] = $this->seancesPourGroupe(
                $groupe,
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
            'palhom_kipranlamaire_premieres_terminales',
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
