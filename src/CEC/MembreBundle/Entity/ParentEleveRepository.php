<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * ParentEleveRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class ParentEleveRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username)
    {
        $query = $this->createQueryBuilder('parent')
            ->where('parent.username = :username')
            ->setParameter('username', $username)
            ->getQuery();

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no unique record matching the criteria
            $user = $query->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Impossible de trouver un lycéen à partir du surnom "%s".', $username), 0, $e);
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

    public function findByUsername($nom, $prenom){
        $query = $this->createQueryBuilder('parent')
            ->where('parent.nom = :nom')
            ->setParameter('nom',$nom)
            ->andWhere('parent.prenom = :prenom')
            ->setParameter('prenom', $prenom)
            ->getQuery();
        return $query->getResult();
    }
}
