<?php

namespace CEC\TutoratBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * LyceeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LyceeRepository extends EntityRepository
{
    /*
     * Retourne les lycées dans des cordées pour l'année spécifiée.
     * Retourne les lycées de l'année actuelle si l'argument n'est pas passé.
     *
     * @param integer $annee: l'année
     * @return array(Lycee)
     */
    public function findAllForYear($annee = null)
    {
        if ( is_null($annee) ) $annee = intval(date('Y'));
        return $this->createQueryBuilder('l')
            ->innerJoin('l.cordees', 'c', 'WITH', 'c.annee = :annee')
            ->setParameter('annee', $annee)
            ->orderBy('l.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    /*
     * Retourne les lycées dans une cordée pour l'année spécifiée.
     * Retourne les lycées de l'année actuelle si l'année n'est pas spécifiée.
     *
     * @param int $cordeeId: l'id de la cordée
     * @param integer $annee: l'année
     * @return array(Lycee)
     */
    public function findAllForCordeeIdAndYear($cordeeId, $annee = null)
    {
        if ( is_null($annee) ) $annee = intval(date('Y'));
        return $this->createQueryBuilder('l')
            ->innerJoin('l.cordees', 'r', 'WITH', 'r.annee = :annee')
            ->setParameter('annee', $annee)
            ->innerJoin('r.cordee', 'c', 'WITH', 'c.id = :cordee_id')
            ->setParameter('cordee_id', $cordeeId)
            ->orderBy('l.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
