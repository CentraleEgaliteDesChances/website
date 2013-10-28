<?php

namespace CEC\MembreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

/**
 * Permet de définir les méthodes de l'interface UserProviderInterface qui chargent les membres
 * et permettent l'authentification en fonction du prénom suivi du nom.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 */
class MembreRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username)
    {
        $query = $this->createQueryBuilder('membre')
            ->where('CONCAT(CONCAT(membre.prenom, \' \'), membre.nom) = :username')
            ->setParameter('username', $username)
            ->getQuery();
        
        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no unique record matching the criteria
            $user = $query->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Impossible de trouver un membre à partir du surnom "%s".', $username), null, 0, $e);
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Les instances de la classe "%s" ne sont pas supportées.', $class));
        }

        return $this->find($user->getId());
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }
    
    /**
     * Recherche les membres bénéficiant des privilèges du buro.
     * Les résultats sont classés par ordre décroissant de promotion (les plus réçentes en premier).
     *
     * @return array Résultats de la recherche.
     */
    public function findBuro() {
        $query = $this->createQueryBuilder('m')
            ->where('m.buro = TRUE')
            ->orderBy('m.promotion', 'DESC')
            ->addOrderBy('m.nom')
            ->addOrderBy('m.prenom')
            ->getQuery();
        return $query->getResult();
    }
    
    /**
     * Retourne le compte des tuteurs actifs pour l'année scolaire indiquée.
     * On retourne le nombre de membres appartenant à un groupe de l'année scolaire.
     *
     * @param AnneeScolaire $anneeScolaire Année scolaire
     * @return integer Compte des tuteurs actifs.
     */
    public function comptePourAnneeScolaire(AnneeScolaire $anneeScolaire)
    {
        $query = $this->createQueryBuilder('m')
            ->select('COUNT(DISTINCT m)')
            ->join('m.groupe', 'g')
            ->where('g.anneeScolaire = :annee_scolaire')
            ->setParameter('annee_scolaire', $anneeScolaire->getAnneeInferieure())
            ->getQuery();
        return $query->getSingleScalarResult();
    }
}
