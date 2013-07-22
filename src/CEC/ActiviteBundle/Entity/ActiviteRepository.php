<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use CEC\ActiviteBundle\Entity\RechercheActivite;
use CEC\TutoratBundle\Entity\Seance;

/**
 * Permet de définir les méthodes de recherche et de filtrage des activités.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 */
class ActiviteRepository extends EntityRepository
{
    /**
     * Effectue la recherche parmi toutes les activités.
     * Les paramètres de la recherche sont passés dans l'objet $recherche, et permettent :
     *     - une recherche dans le titre ;
     *     - une recherche dans le type ;
     *     - un filtrage des activités qui n'ont pas été réalisées en séance du groupe de tutorat
     *       de l'utilisateur.
     *
     * @param CEC\ActiviteBundle\Entity\RechercheActivite $recherche Paramètres de la recherche
     * @return array Résultats de la recherche.
     */
    public function findWithRechercheActivite(RechercheActivite $recherche)
    {
        $dql = 'SELECT a FROM CECActiviteBundle:Activite a
                WHERE LOWER(a.titre) LIKE :titre
                AND LOWER(a.type) LIKE :type';
        
        if ($recherche->getFiltrerActivitesRealisees()) {
            $dql .= ' AND (
                        SELECT COUNT(cr) FROM CECActiviteBundle:CompteRendu cr
                        JOIN CECTutoratBundle:Seance s
                        WITH cr.seance = s.id
                        AND s.groupe = :groupe_id
                        WHERE cr.activite = a.id
                    ) = 0';
        }
        
        $requete = $this->getEntityManager()->createQuery($dql)
            ->setParameter('titre', '%' . $recherche->getTitre() . '%')
            ->setParameter('type', '%' . $recherche->getType() . '%');
        if ($recherche->getFiltrerActivitesRealisees()) $requete->setParameter('groupe_id', $recherche->getGroupe()->getId());
        
        return $requete->getResult();
    }
    
    /**
     * Recherche les activités réalisées au cours d'une séance.
     * Permet de retrouver les activités présentes dans les compte-rendus associés à une séance.
     *
     * @param CEC\TutoratBundle\Entity\Seance $seance : séance pour laquelle on veut retrouver les activités
     * @return array Résultats de la recherche.
     */
    public function findBySeance(Seance $seance)
    {
        $query = $this->createQueryBuilder('a')
            ->join('a.compteRendus', 'cr')
            ->where('cr.seance = :seance_id')
            ->setParameter('seance_id', $seance->getId())
            ->getQuery();
        return $query->getResult();
    }
}
