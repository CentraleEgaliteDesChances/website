<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CEC\MembreBundle\Entity\Membre;

class ChargeMembres implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $membre = new Membre();
        $membre->setPrenom('Paul')
            ->setNom('Maire')
            ->setEmail('paul.maire@student.ecp.fr')
            ->setTelephone('0608028328');
        
        $encoder = $this->container
                    ->get('security.encoder_factory')
                    ->getEncoder($membre);
        $membre->setMotDePasse($encoder->encodePassword('debug', $membre->getSalt()));
        
        $manager->persist($membre);
        $manager->flush();
    }
}
