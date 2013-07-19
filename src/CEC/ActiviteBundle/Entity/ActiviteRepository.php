<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use CEC\ActiviteBundle\Entity\RechercheActivite;

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
     * Les paramètres de la recherche sont passés dans l'objet $recherche.
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
                        WHERE cr.seance = s.id
                        AND s.groupe = :groupe_id
                    ) > 0';
        }
        
        $requete = $this->getEntityManager()->createQuery($dql)
            ->setParameter('titre', '%' . $recherche->getTitre() . '%')
            ->setParameter('type', '%' . $recherche->getType() . '%');
        if ($recherche->getFiltrerActivitesRealisees()) $requete->setParameter('groupe_id', $recherche->getGroupe()->getId());
        
        return $requete->getResult();
    }
}
