<?php

namespace CEC\SecteurProjetsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\SecteurProjetsBundle\Entity\Album;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class LoadAlbums extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        $anneeA = date('Y');

        $anneeP = date('Y') -1;

        // Album de l'année actuelle

        $album1 = new Album();
        $album1->setProjet($this->getReference('theatre'))
            ->setAnnee($anneeA);

        $album2 = new Album();
        $album2->setProjet($this->getReference('focus'))
            ->setAnnee($anneeA);

        // Albums de l'année passée

        $album3 = new Album();
        $album3->setProjet($this->getReference('theatre'))
            ->setAnnee($anneeP);

        $album4 = new Album();
        $album4->setProjet($this->getReference('gml'))
            ->setAnnee($anneeP);


        $manager->persist($album1);
        $manager->persist($album2);
        $manager->persist($album3);
        $manager->persist($album4);

        $manager->flush();

        $this->addReference('album1', $album1);
        $this->addReference('album2', $album2);
        $this->addReference('album3', $album3);
        $this->addReference('album4', $album4);
    }

    /**
    * {@inheritDoc()}
    */
    public function getDependencies()
    {
        return array(
            'CEC\SecteurProjetsBundle\DataFixtures\ORM\LoadProjets'
            );
    }
}