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
    /**
     * Retourne le compte des lycées actifs pour l'année scolaire indiquée.
     * On retourne le nombre de lycées appartenant à une cordée de la réussite.
     *
     * @return integer Compte des lycées actifs.
     */
    public function compte()
    {
        $query = $this->createQueryBuilder('l')
            ->select('COUNT(DISTINCT l)')
            ->join('l.cordee', 'c')
            ->getQuery();
        return $query->getSingleScalarResult();
    }
}
