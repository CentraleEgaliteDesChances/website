<?php

namespace CEC\SecteurProjetsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\SecteurProjetsBundle\Entity\Reunion;

class LoadReunions extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        $anneeP = date('Y');

        $reunion1 = new Reunion();
        $reunion1->setNom("Réunion d'information pour le Stage Théâtre")
            ->setDate(new \DateTime($anneeP.'-01-10'))
            ->setHeureDebut(new \DateTime($anneeP.'-01-10T19:00:00'))
            ->setHeureFin(new \DateTime($anneeP.'-01-10T20:00:00'))
            ->setAdresse('Centrale')
            ->setDescription('Cette réunion présentera le stage théâtre en détail.')
            ->setProjet($this->getReference('theatre'));
        $reunion2 = new Reunion();
        $reunion2->setNom("Réunion d'information pour Focus Europe")
            ->setDate(new \DateTime($anneeP.'-03-16'))
            ->setHeureDebut(new \DateTime($anneeP.'-03-16T19:30:00'))
            ->setHeureFin(new \DateTime($anneeP.-'03-16T20:15:00'))
            ->setAdresse("Jean Jaurès")
            ->setDescription("Réunion pour présenter Focus Europe")
            ->setProjet($this->getReference('focus'));
        $reunion3 = new Reunion();
        $reunion3->setNom("Réunion d'information pour présenter GML")
            ->setDate(new \DateTime($anneeP.'-04-23'))
            ->setHeureDebut(new \DateTime($anneeP.'-04-23T18:15:00'))
            ->setHeureFin(new \DateTime($anneeP.'-04-23T19:45:00'))
            ->setAdresse("Centrale")
            ->setDescription("Réunion pour parler du voyage à Londres")
            ->setProjet($this->getReference('gml'));
        $reunion4 = new Reunion();
        $reunion4->setNom("Réunion d'information pour le Stage Théâtre")
            ->setDate(new \DateTime($anneeA.'-01-10'))
            ->setHeureDebut(new \DateTime($anneeA.'-01-10T19:00:00'))
            ->setHeureFin(new \DateTime($anneeA.'-01-10T20:00:00'))
            ->setAdresse('Centrale')
            ->setDescription('Cette réunion présentera le stage théâtre en détail.')
            ->setProjet($this->getReference('theatre'));

            // Réunion qui a lieu le lendemain du test pour pouvoir tester les inscriptions

        $lendemain = date('Y-m-d');
        $lendemain = new \DateTime($lendemain);
        $lendemain->add(new \DateInterval('P1D'));

        $reunion5 = new Reunion();
        $reunion5->setNom("Réunion d'information pour présenter GML")
            ->setDate($lendemain)
            ->setHeureDebut($lendemain->add(new \DateInterval('PT18H15M')))
            ->setHeureFin($lendemain->add(new \DateInterval('PT19H45M')))
            ->setAdresse("Centrale")
            ->setDescription("Réunion pour parler du voyage à Londres")
            ->setProjet($this->getReference('gml'));

        // Ajout de présents aux réunions
        $reunion1->addPresent($this->getReference('nesrine_abada'))
            ->addPresent($this->getReference('nelson_melo'))
            ->addPresent($this->getReference('lauren_doucet'))
            ->addPresent($this->getReference('ines_chiandotto'))
            ->addPresent($this->getReference('yanis_felix'))
            ->addPresent($this->getReference('titouan_de_souza'))
            ->addPresent($this->getReference('claire_alves'));
        $reunion2->addPresent($this->getReference('aude_ambrosini'))
            ->addPresent($this->getReference('ines_chiandotto'))
            ->addPresent($this->getReference('mateo_sachet'))
            ->addPresent($this->getReference('emma_gausson'));
        $reunion3->addPresent($this->getReference('anna_michel'))
            ->addPresent($this->getReference('aude_ambrosini'))
            ->addPresent($this->getReference('arnaud_milome'))
            ->addPresent($this->getReference('ines_chiandotto'))
            ->addPresent($this->getReference('lauren_doucet'));
        $reunion4->addPresent($this->getReference('mehdi_ferdoss'))
            ->addPresent($this->getReference('arno_dubois'))
            ->addPresent($this->getReference('claire_alves'))
            ->addPresent($this->getReference('titouan_de_souza'))
            ->addPresent($this->getReference('mateo_sachet'));
        $reunion5->addPresent($this->getReference('mateo_sachet'))
            ->addPresent($this->getReference(('yanis_felix')))
            ->addPresent($this->getReference('ines_chiandotto'))
            ->addPresent($this->getReference('noemie_grapindor'))
            ->addPresent($this->getReference('emma_gausson'));

        $manager->persist($reunion1);
        $manager->persist($reunion2);
        $manager->persist($reunion3);
        $manager->persist($reunion4);
        $manager->persist($reunion5);

        $manager->flush();

    }

    /**
    * {@inheritDoc()}
    */
    public function getDependencies()
    {
        return array(
            'CEC\SecteurProjetsBundle\DataFixtures\ORM\LoadProjets',
            'CEC\MembreBundle\DataFixtures\ORM\LoadEleves'
            );
    }
}