<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use CEC\ActiviteBundle\Utility\RechercheActivite;
use CEC\TutoratBundle\Entity\Seance;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

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
     * @param CEC\ActiviteBundle\Utility\RechercheActivite $recherche Paramètres de la recherche
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
        
        $dql .= ' ORDER BY a.titre';
        
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
            ->orderBy('a.titre', 'ASC')
            ->setParameter('seance_id', $seance->getId())
            ->getQuery();
        return $query->getResult();
    }
    
    /**
     * Retourne le compte des activités.
     * On retourne le nombre d'activités de la base de donnée.
     *
     * @return integer Nombre d'activités.
     */
    public function compte()
    {
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(DISTINCT a)')
            ->getQuery();
        return $query->getSingleScalarResult();
    }
    
    /**
     * Retourne le compte des activités utilisées pendant l'année scolaire indiquée.
     * On retourne le nombre d'activités possédant un compte-rendu attaché à une séance,
     * elle-même associée à un groupe actif durant l'année scolaire donnée en argument.
     *
     * @param AnneeScolaire $anneeScolaire Année scolaire
     * @return integer Compte des activités utilisées.
     */
    public function compteUtiliseesPourAnneeScolaire(AnneeScolaire $anneeScolaire)
    {
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(DISTINCT a)')
            ->join('a.compteRendus', 'cr')
            ->join('cr.seance', 's')
            ->where('s IN (SELECT seance FROM CECTutoratBundle:Seance seance JOIN seance.groupe gr JOIN gr.tuteursParAnnee gt WHERE gt.anneeScolaire = :annee_scolaire)')
            ->setParameter('annee_scolaire', $anneeScolaire->getAnneeInferieure())
            ->getQuery();
        return $query->getSingleScalarResult();
    }

    /**
     * Retourne les activités utilisées pendant l'année scolaire indiquée.
     * On retourne les activités possédant un compte-rendu attaché à une séance,
     * elle-même associée à un groupe actif durant l'année scolaire donnée en argument.
     *
     * @param AnneeScolaire $anneeScolaire Année scolaire
     * @return \Doctrine\Common\Collection\Collections activités utilisées.
     */
    public function utiliseesPourAnneeScolaire(AnneeScolaire $anneeScolaire)
    {
        $query = $this->createQueryBuilder('a')
            ->select('DISTINCT a')
            ->join('a.compteRendus', 'cr')
            ->join('cr.seance', 's')
            ->where('s IN (SELECT seance FROM CECTutoratBundle:Seance seance JOIN seance.groupe gr JOIN gr.tuteursParAnnee gt WHERE gt.anneeScolaire = :annee_scolaire)')
            ->setParameter('annee_scolaire', $anneeScolaire->getAnneeInferieure())
            ->getQuery();
        return $query->getResult();
    }

}
