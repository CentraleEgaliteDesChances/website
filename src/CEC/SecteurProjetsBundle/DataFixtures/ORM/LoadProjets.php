<?php

namespace CEC\SecteurProjetsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\SecteurProjetsBundle\Entity\Projet;

class LoadProjets extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        $annee = date('Y');

        $theatre = new Projet();
        $theatre->setNom('Stage Théâtre')
            ->setSlug('stage-theatre')
            ->setDescription('Stage de théâtre de 3 jours à Centrale')
            ->setDescriptionCourte('Stage de théâtre')
            ->setDateDebut(new \DateTime($annee.'-03-03'))
            ->setDateFin(new \DateTime($annee.'-03-06'))
            ->setLieu('Centrale Paris')
            ->setInscriptionsOuvertes(true)
            ->addContact($this->getReference('tristan_pouliquen'));

        $focus = new Projet();
        $focus->setNom('Focus Europe')
            ->setSlug('focus-europe')
            ->setDescription('Découverte de 4 jours d\'une ville 
                européenne et de sa culture')
            ->setDescriptionCourte('Découverte d\'une ville européenne ')
            ->setDateDebut(new \DateTime($annee.'-04-20'))
            ->setDateFin(new \DateTime($annee.'-04-24'))
            ->setLieu('Madrid, Espagne')
            ->setInscriptionsOuvertes(false)
            ->addContact($this->getReference('gurvan_hermange'));

        $gml = new Projet();
        $gml->setNom('Good Morning London')
            ->setSlug('good-morning-london')
            ->setDescription('Voyage linguistique de 10 jours à Londres sur 
                le thème du journalisme')
            ->setDescriptionCourte('Voyage linguistique')
            ->setDateDebut(new \DateTime($annee.'-07-21'))
            ->setDateFin(new \DateTime($annee.'-07-31'))
            ->setLieu('Londres, Royaume-Uni')
            ->setInscriptionsOuvertes(true)
            ->addContact($this->getReference('ml_charpignon'))
            ->addContact($this->getReference('pol_maire'));


        $manager->persist($theatre);
        $manager->persist($focus);
        $manager->persist($gml);

        $manager->flush();

        $this->addReference('theatre', $theatre);
        $this->addReference('focus', $focus);
        $this->addReference('gml', $gml);

    }

    /**
    * {@inheritDoc()}
    */
    public function getDependencies()
    {
        return array(
            'CEC\MembreBundle\DataFixtures\ORM\LoadMembres'
            );
    }
}