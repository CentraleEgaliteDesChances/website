<?php

namespace CEC\SecteurProjetsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DossierRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DossierRepository extends EntityRepository
{
	public function loadDossier($projet)
    {
        $query = $this->createQueryBuilder('dossier')
            ->where('dossier.projet = :projet')
            ->setParameter('projet', $projet)
            ->getQuery();
        
        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no unique record matching the criteria
            $dossier = $query->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Impossible de trouver un projet à partir du nom "%s".', $slug), null, 0, $e);
        }

        return $dossier;
    }
}
