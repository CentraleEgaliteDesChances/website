<?php

namespace CEC\SecteurSortiesBundle\Entity;

use Doctrine\ORM\EntityRepository;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

/**
 * Permet de définir les méthodes de recherche des sorties.
 *
 * @author Corentin Bertrand
 * @version 1.0
 */
class SortieRepository extends EntityRepository
{
    /**
     * Recherche toutes les sorties qui ont eu lieu ou auront lieu APRES $date.
     * Si $date = new \DateTime("now"); on retrouve toutes les sorties à venir.
     *
     * @param date $date : date après laquelle on recherche les sorties
     * @return array Résultats de la recherche.
     */
    public function findFollowingSorties($date)
    {
        $dql = 'SELECT a FROM CECSecteurSortiesBundle:Sortie a
                WHERE a.dateSortie >= :date
                ORDER BY a.dateSortie';

        $requete = $this->getEntityManager()->createQuery($dql)
            ->setParameter('date', $date->format('Y-m-d'));

        return $requete->getResult();
    }


    /**
     * Recherche toutes les sorties qui ont eu lieu ou auront lieu AVANT $date.
     * Si $date = new \DateTime("now"); on retrouve toutes les sorties passées.
     *
     * @param date $date : date avant laquelle on recherche les sorties
     * @return array Résultats de la recherche.
     */
    public function findPreviousSorties($date)
    {
        $dql = 'SELECT a FROM CECSecteurSortiesBundle:Sortie a
                WHERE a.dateSortie < :date
                ORDER BY a.dateSortie DESC';

        $requete = $this->getEntityManager()->createQuery($dql)
            ->setParameter('date', $date->format('Y-m-d'));

        return $requete->getResult();
    }

    /**
     * Récupère les sorties se déroulant entre deux dates spécifiques.
     *
     * @param \DateTime $dateDebut : date de début
     * @param \DateTime $dateFin   : date de fin
     * @return ArrayCollection
     */
    public function findAllBetweenDates(\DateTime $dateDebut, \DateTime $dateFin)
    {
        $query = $this->createQueryBuilder('s')
                      ->where('s.dateSortie BETWEEN :date_debut and :date_fin')
                      ->setParameter('date_debut', $dateDebut->format('Y-m-d H:i:s'))
                      ->setParameter('date_fin', $dateFin->format('Y-m-d H:i:s'))
                      ->getQuery();
        return $query->getResult();
    }

    /**
     * Retourne le compte des sorties passées l'année scolaire indiquée.
     *
     * @param AnneeScolaire $anneeScolaire Année scolaire
     * @return integer Compte des sorties effectuées.
     */
    public function comptePourAnneeScolaire(AnneeScolaire $anneeScolaire)
    {
        $query = $this->createQueryBuilder('s')
            ->select('COUNT(DISTINCT s)')
            ->where('s.anneeScolaire = :annee_scolaire')
            ->setParameter('annee_scolaire', $anneeScolaire->getAnneeInferieure())
            ->getQuery();
        return $query->getSingleScalarResult();
    }

    /**
     * Retourne le nombre de lycéens qui ont participé à une sortie dans l'année scolaire $anneeScolaire.
     * On compte deux fois un lycéens qui est venu à deux sorties, c'est donc plutôt le nombre de participations aux sorties.
     *
     * @param AnneeScolaire $anneeScolaire Année scolaire
     * @return integer Somme du nombre de lycéens venu pour chaque sortie pendant l'année scolaire.
     */
    public function compteLyceensSortiePourAnneeScolaire(AnneeScolaire $anneeScolaire)
    {
        $query = $this->createQueryBuilder('s')
            ->select('DISTINCT s')
            ->where('s.anneeScolaire = :annee_scolaire')
            ->setParameter('annee_scolaire', $anneeScolaire->getAnneeInferieure())
            ->getQuery();
        $resultats = $query->getResult();

        $nbLyceens = 0;
        foreach ($resultats as $sortie) {
            $nbLyceens += count($sortie->getLyceens());
        }
        return $nbLyceens;
    }
}
