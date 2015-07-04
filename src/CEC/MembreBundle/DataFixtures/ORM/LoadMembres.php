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
            ->setMail('paul.maire@student.ecp.fr')
            ->setTelephone('0607080910')
            ->setPromotion('2013')
            ->setBuro(true)
            ->setCheckMail(false)
            ->updateRoles();
            
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($pol_maire);
        $mdp = $encoder->encodePassword('debug', $pol_maire->getSalt());
        
        $pol_maire->setMotDePasse($mdp);
        
        $helene_sicsic = new Membre();
        $helene_sicsic->setPrenom('Hélène')
            ->setNom('Sicsic')
            ->setMail('helene.sicsic@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2013')
            ->addSecteur($this->getReference('secteur_activites_culturelles'))
            ->setBuro(false)
            ->setCheckMail(false)
            ->updateRoles();
        
        $jb_bayle = new Membre();
        $jb_bayle->setPrenom('Jean-Baptiste')
            ->setNom('Bayle')
            ->setMail('jean-baptiste.bayle@student.ecp.fr')
            ->setTelephone('06 08 02 83 25')
            ->setMotDePasse($mdp)
            ->setPromotion('2014')
            ->setBuro(true)
            ->setCheckMail(false)
            ->updateRoles();
            
        $eloise_vailland = new Membre();
        $eloise_vailland->setPrenom('Eloise')
            ->setNom('Vailland')
            ->setMail('eloise.vailland@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->setBuro(true)
            ->setCheckMail(false)
            ->updateRoles();
            
        $charles_giachetti = new Membre();
        $charles_giachetti->setPrenom('Charles')
            ->setNom('Giachetti')
            ->setMail('charles.giachetti@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->setBuro(true)
            ->setCheckMail(false)
            ->updateRoles();
            
        $paul_chauchat = new Membre();
        $paul_chauchat->setPrenom('Paul')
            ->setNom('Chauchat')
            ->setMail('paul.chauchat@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->setBuro(true)
            ->setCheckMail(false)
            ->updateRoles();
        
        $ml_charpignon = new Membre();
        $ml_charpignon->setPrenom('Marie-Laure')
            ->setNom('Charpignon')
            ->setMail('marie---laure.charpignon-choquet@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->setBuro(true)
            ->setCheckMail(false)
            ->updateRoles();
            
        $thomas_beligne = new Membre();
        $thomas_beligne->setPrenom('Thomas')
            ->setNom('Beligné')
            ->setMail('paul.beligne@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2014')
            ->setBuro(false)
            ->setCheckMail(false)
            ->updateRoles();
            
        $jean_philippe_de_la_taillardiere = new Membre();
        $jean_philippe_de_la_taillardiere->setPrenom('Jean-Philippe')
            ->setNom('De La Taillardière')
            ->setMail('jean-philippe.de-la-taillardiere@student.ecp.fr')
            ->setTelephone('0709080706')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->setBuro(false)
            ->setCheckMail(false)
            ->updateRoles();
            
        $gurvan_hermange = new Membre();
        $gurvan_hermange->setPrenom('Gurvan')
            ->setNom('Hermange')
            ->setMail('gurvan.hermange@student.ecp.fr')
            ->setTelephone('00 00 00 00 00')
            ->setMotDePasse($mdp)
            ->setPromotion('2015')
            ->addSecteur($this->getReference('secteur_evenements'))
            ->setBuro(false)
            ->setCheckMail(false)
            ->updateRoles();
        
        $tristan_pouliquen = new Membre();
        $tristan_pouliquen->setPrenom('Tristan')
            ->setNom('Pouliquen')
            ->setMail('tristan.pouliquen@student.ecp.fr')
            ->setTelephone('0638396593')
            ->setMotDePasse($mdp)
            ->setPromotion('2016')
            ->addSecteur($this->getReference('secteur_activites_culturelles'))
            ->addSecteur($this->getReference('secteur_sorties'))
            ->setBuro(true)
            ->setCheckMail(false)
            ->updateRoles();

        $gabrielle_jourdain = new Membre();
        $gabrielle_jourdain->setPrenom('Gabrielle')
            ->setNom('Jourdain')
            ->setMail('gabrielle.jourdain@student.ecp.fr')
            ->setTelephone('0638396593')
            ->setMotDePasse($mdp)
            ->setPromotion('2016')
            ->addSecteur($this->getReference('secteur_activites_culturelles'))
            ->setBuro(true)
            ->setCheckMail(false)
            ->updateRoles();

        $manager->persist($pol_maire);
        $manager->persist($helene_sicsic);
        $manager->persist($jb_bayle);
        $manager->persist($eloise_vailland);
        $manager->persist($charles_giachetti);
        $manager->persist($paul_chauchat);
        $manager->persist($ml_charpignon);
        $manager->persist($thomas_beligne);
        $manager->persist($gurvan_hermange);
        $manager->persist($jean_philippe_de_la_taillardiere);
        $manager->persist($tristan_pouliquen);
        $manager->persist($gabrielle_jourdain);
        $manager->flush();
        
        $this->addReference('pol_maire', $pol_maire);
        $this->addReference('helene_sicsic', $helene_sicsic);
        $this->addReference('gabrielle_jourdain', $gabrielle_jourdain);
        $this->addReference('jb_bayle', $jb_bayle);
        $this->addReference('eloise_vailland', $eloise_vailland);
        $this->addReference('charles_giachetti', $charles_giachetti);
        $this->addReference('paul_chauchat', $paul_chauchat);
        $this->addReference('ml_charpignon', $ml_charpignon);
        $this->addReference('thomas_beligne', $thomas_beligne);
        $this->addReference('gurvan_hermange', $gurvan_hermange);
        $this->addReference('jean_philippe_de_la_taillardiere', $jean_philippe_de_la_taillardiere);
        $this->addReference('tristan_pouliquen', $tristan_pouliquen);
        
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
