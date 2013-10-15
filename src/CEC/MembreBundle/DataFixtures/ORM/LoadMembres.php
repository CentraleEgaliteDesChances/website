<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\MembreBundle\Entity\Membre;

class LoadMembres extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
            ->setTelephone('0607080910')
            ->setPromotion('2013')
            ->setBuro(true);
            
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($pol_maire);
        $mdp = $encoder->encodePassword('debug', $pol_maire->getSalt());
        
        $pol_maire->setMotDePasse($mdp);
        
        $helene_sicsic = new Membre();
        $helene_sicsic->setPrenom('Hélène')
            ->setNom('Sicsic')
            ->setEmail('helene.sicsic@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2013')
            ->addSecteur($this->getReference('secteur_activites_culturelles'))
            ->setBuro(false);
        
        $jb_bayle = new Membre();
        $jb_bayle->setPrenom('Jean-Baptiste')
            ->setNom('Bayle')
            ->setEmail('jean-baptiste.bayle@student.ecp.fr')
            ->setTelephone('06 08 02 83 25')
            ->setMotDePasse($mdp)
            ->setPromotion('2014')
            ->setBuro(true);
            
        $eloise_vailland = new Membre();
        $eloise_vailland->setPrenom('Eloise')
            ->setNom('Vailland')
            ->setEmail('eloise.vailland@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->setBuro(true);
            
        $charles_giachetti = new Membre();
        $charles_giachetti->setPrenom('Charles')
            ->setNom('Giachetti')
            ->setEmail('charles.giachetti@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->setBuro(true);
            
        $paul_chauchat = new Membre();
        $paul_chauchat->setPrenom('Paul')
            ->setNom('Chauchat')
            ->setEmail('paul.chauchat@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->setBuro(true);
        
        $ml_charpignon = new Membre();
        $ml_charpignon->setPrenom('Marie-Laure')
            ->setNom('Charpignon')
            ->setEmail('marie---laure.charpignon-choquet@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->setBuro(true);
            
        $thomas_beligne = new Membre();
        $thomas_beligne->setPrenom('Thomas')
            ->setNom('Beligné')
            ->setEmail('paul.beligne@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2014')
            ->setBuro(false);
            
        $gurvan_hermange = new Membre();
        $gurvan_hermange->setPrenom('Gurvan')
            ->setNom('Hermange')
            ->setEmail('gurvan.hermange@student.ecp.fr')
            ->setTelephone('00 00 00 00 00')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->addSecteur($this->getReference('secteur_evenements'))
            ->setBuro(false);
        
        $manager->persist($pol_maire);
        $manager->persist($helene_sicsic);
        $manager->persist($jb_bayle);
        $manager->persist($eloise_vailland);
        $manager->persist($charles_giachetti);
        $manager->persist($paul_chauchat);
        $manager->persist($ml_charpignon);
        $manager->persist($thomas_beligne);
        $manager->persist($gurvan_hermange);
        $manager->flush();
        
        $this->addReference('pol_maire', $pol_maire);
        $this->addReference('helene_sicsic', $helene_sicsic);
        $this->addReference('jb_bayle', $jb_bayle);
        $this->addReference('eloise_vailland', $eloise_vailland);
        $this->addReference('charles_giachetti', $charles_giachetti);
        $this->addReference('paul_chauchat', $paul_chauchat);
        $this->addReference('ml_charpignon', $ml_charpignon);
        $this->addReference('thomas_beligne', $thomas_beligne);
        $this->addReference('gurvan_hermange', $gurvan_hermange);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\MembreBundle\DataFixtures\ORM\LoadSecteurs',
        );
    }
}
