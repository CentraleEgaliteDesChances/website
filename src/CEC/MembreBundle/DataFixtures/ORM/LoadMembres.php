<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\MembreBundle\Entity\Membre;

class LoadMembres extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $pol_maire = new Membre();
        $pol_maire->setPrenom('Pol')
            ->setNom('Maire')
            ->setEmail('paul.maire@student.ecp.fr')
            ->setTelephone('0608028328')
            ->addSecteur($this->getReference('secteur_sorties'))
            ->addSecteur($this->getReference('secteur_projets'))
            ->setGroupe($this->getReference('jj_premieres'));
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($pol_maire);
        $pol_maire->setMotDePasse($encoder->encodePassword('debug', $pol_maire->getSalt()));
        
        $helene_sicsic = new Membre();
        $helene_sicsic->setPrenom('Hélène')
            ->setNom('Sicsic')
            ->setEmail('helene.sicsic@student.ecp.fr')
            ->setTelephone('0709080706')
            ->addSecteur($this->getReference('secteur_projets'))
            ->setGroupe($this->getReference('jj_premieres'));
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($helene_sicsic);
        $helene_sicsic->setMotDePasse($encoder->encodePassword('debug', $helene_sicsic->getSalt()));
        
        $manager->persist($pol_maire);
        $manager->persist($helene_sicsic);
        $manager->flush();
        
        $this->addReference('pol_maire', $pol_maire);
        $this->addReference('helene_sicsic', $helene_sicsic);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 50;
    }
}
