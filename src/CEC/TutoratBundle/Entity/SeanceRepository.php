<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

/**
 * SeanceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SeanceRepository extends EntityRepository
{
    /**
     * Récupère les séances qui sont encore à venir pour un groupe.
     * On filtre les séances en ne considérant que celles qui se déroule aujourd'hui et antérieures.
     * On applique aussi un tri croissant par date de début.
     * On se limite par défaut aux 5 prochaines séances.
     *
     * @param Groupe $groupe: the groupe
     * @return ArrayCollection
     */
    public function findComingByGroupe(Groupe $groupe, $limite = 5)
    {
        $query = $this->createQueryBuilder('s')
            ->where('s.groupe = :groupe_id')
            ->setParameter('groupe_id', $groupe->getId())
            ->andWhere('s.date > :maintenant')
            ->setParameter('maintenant', new \DateTime())
            ->orderBy('s.date', 'ASC')
            ->setMaxResults($limite)
            ->getQuery();
        return $query->getResult();
    }
    
    /**
     * Récupère les séances se déroulant entre deux dates spécifiques.
     *
     * @param \DateTime $dateDebut : date de début
     * @param \DateTime $dateFin   : date de fin
     * @return ArrayCollection
     */
    public function findAllBetweenDates(\DateTime $dateDebut, \DateTime $dateFin)
    {
        $query = $this->createQueryBuilder('s')
                      ->where('s.date BETWEEN :date_debut and :date_fin')
                      ->setParameter('date_debut', $dateDebut->format('Y-m-d H:i:s'))
                      ->setParameter('date_fin', $dateFin->format('Y-m-d H:i:s'))
                      ->getQuery();
        return $query->getResult();
    }
    
    /**
     * Retourne la séance à venir pour le groupe de tutorat.
     * Une séance de tutorat est dite "à venir" si les conditions suivantes sont remplies :
     *     - la séance a lieu dans moins d'une semaine ;
     *     - aucune autre séance n'est programmée avant.
     * Si aucune séance n'est à venir, la méthode renvoit 'false'.
     *
     * @param Groupe $groupe : le groupe de tutorat pour lequel on cherche la séance.
     * @return Seance La séance à venir, ou 'false'
     */
    public function findOneAVenir(Groupe $groupe)
    {
        $query = $this->createQueryBuilder('s')
                      ->where("s.date BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), 7, 'DAY')")
                      ->andWhere('s.groupe = :groupe_id')
                      ->orderBy('s.date', 'ASC')
                      ->setParameter('groupe_id', $groupe->getId())
                      ->setMaxResults(1)
                      ->getQuery();
        $resultat = $query->getResult();
        return count($resultat) > 0 ? $resultat[0] : false;
    }
    
    /**
     * Retourne le compte des séances de tutorat données pour l'année scolaire indiquée.
     * On retourne le nombre de séances dont le groupe est de l'année indiquée.
     *
     * @param AnneeScolaire $anneeScolaire Année scolaire
     * @return integer Compte des séances données.
     */
    public function comptePourAnneeScolaire(AnneeScolaire $anneeScolaire)
    {
        $query = $this->createQueryBuilder('s')
            ->select('COUNT(DISTINCT s)')
            ->join('s.groupe', 'g')
            ->join('g.tuteursParAnnee', 'gt')
            ->where('gt.anneeScolaire = :annee_scolaire')
                ->setParameter('annee_scolaire', $anneeScolaire->getAnneeInferieure())
            ->andWhere('s.date < :now')
                ->setParameter('now', new \DateTime())
            ->getQuery();
        return $query->getSingleScalarResult();
    }
    
    /**
     * Retourne le nombre d'heures de tutorat dispensées pour l'année scolaire indiquée.
     * On retourne la somme des heures de tutorat pour les séances dont le groupe est de l'année
     * scolaire indiquée.
     *
     * @param AnneeScolaire $anneeScolaire Année scolaire
     * @return integer Somme des heures de tutorat dispensées pendant l'année scolaire.
     */
    public function compteHeuresTutoratPourAnneeScolaire(AnneeScolaire $anneeScolaire)
    {
        $query = $this->createQueryBuilder('s')
            ->select('DISTINCT s')
            ->join('s.groupe', 'g')
            ->join('g.tuteursParAnnee', 'gt')
            ->where('gt.anneeScolaire = :annee_scolaire')
                ->setParameter('annee_scolaire', $anneeScolaire->getAnneeInferieure())
            ->andWhere('s.date < :now')
                ->setParameter('now', new \DateTime())
            ->getQuery();
        $resultats = $query->getResult();
        
        $minutesTutorat = 0;
        foreach ($resultats as $seance) {
            $duree = $seance->retreiveFin()->diff($seance->retreiveDebut());
            $minutesTutorat += $duree->h * 60 + $duree->i;
        }
        return floor($minutesTutorat / 60);
    }

    /**
     * Récupère toutes les séances pour un groupe
     * On se limite par défaut aux 3 dernières années
     *
     * @param Groupe $groupe: the groupe
     * @return ArrayCollection
     */
    public function findAllSeanceThisYearByGroupe(Groupe $groupe)
    {
        $anneeactuel = AnneeScolaire::withDate();
        $datelimit = $anneeactuel->getDateRentree();
        $query = $this->createQueryBuilder('s')
            ->where('s.groupe = :groupe_id')
            ->setParameter('groupe_id', $groupe->getId())
            ->andWhere('s.date > :datelimit')
            ->setParameter('datelimit', $datelimit)
            ->orderBy('s.date', 'ASC')
            ->getQuery();
        return $query->getResult();
    }
}
