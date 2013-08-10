<?php

namespace CEC\MembreBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use CEC\MembreBundle\Entity\Exception\NomUtilisateurInvalideException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

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
        $usernameExploded = explode(' ', $username);
        if (count($usernameExploded) <> 2) {
            throw new NomUtilisateurInvalideException(sprintf('Bad credentials format'), null, 0);
        }
        $prenom = $usernameExploded[0];
        $nom = $usernameExploded[1];
    
        $q = $this
            ->createQueryBuilder('m')
            ->where('m.prenom = :prenom and m.nom = :nom')
            ->setParameter('prenom', $prenom)
            ->setParameter('nom', $nom)
            ->getQuery()
        ;

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Unable to find an active admin AcmeUserBundle:User object identified by "%s".', $username), null, 0, $e);
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
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
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
            ->getQuery();
        return $query->getResult();
    }
    
}
