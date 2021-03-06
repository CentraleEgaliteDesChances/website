<?php

namespace CEC\SecteurProjetsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProjetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjetRepository extends EntityRepository
{

    public function loadProjet($slug)
    {
        $query = $this->createQueryBuilder('projet')
            ->where('projet.slug = :projet')
            ->setParameter('projet', $slug)
            ->getQuery();
        
        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no unique record matching the criteria
            $projet = $query->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Impossible de trouver un projet à partir du nom "%s".', $slug), null, 0, $e);
        }

        return $projet;
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
        $query = $this->createQueryBuilder('projet')
                      ->where('projet.dateDebut < :date_fin OR projet.dateFin > :date_debut')
                      ->setParameter('date_debut', $dateDebut->format('Y-m-d H:i:s'))
                      ->setParameter('date_fin', $dateFin->format('Y-m-d H:i:s'))
                      ->getQuery();
        return $query->getResult();
    }
}
