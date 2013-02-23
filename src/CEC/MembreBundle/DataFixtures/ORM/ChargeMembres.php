<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\MembreBundle\Entity\Membre;

class ChargeMembres extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $membre->setPrenom('Pol')
            ->setNom('Maire')
            ->setEmail('paul.maire@student.ecp.fr')
            ->setTelephone('0608028328');
        
        $encoder = $this->container
                    ->get('security.encoder_factory')
                    ->getEncoder($membre);
        $membre->setMotDePasse($encoder->encodePassword('debug', $membre->getSalt()));
        
        $manager->persist($membre);
        $manager->flush();
        
        $this->addReference('membre', $membre);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1;
    }
}
