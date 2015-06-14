<?php

namespace CEC\SecteurProjetsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\SecteurProjetsBundle\Entity\ProjetEleve;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class LoadProjetEleves extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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

        $annee = AnneeScolaire::withDate();
        $anneeP = AnneeScolaire::withDate();
        $anneeP->setAnneeInferieure($anneeP->getAnneeInferieure()-1);
        $inscrit = array();
        // Inscription au stage théâtre


            // Année précédente

            $inscrit['1'] = new ProjetEleve();
            $inscrit['1']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('leo_decoodt'));
            $inscrit['2'] = new ProjetEleve();
            $inscrit['2']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('anna_michel'));
            $inscrit['3'] = new ProjetEleve();
            $inscrit['3']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('lucile_gasparini'));
            $inscrit['4'] = new ProjetEleve();
            $inscrit['4']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('ines_chiandotto'));
            $inscrit['5'] = new ProjetEleve();
            $inscrit['5']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('arno_dubois'));
            $inscrit['6'] = new ProjetEleve();
            $inscrit['6']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('karim_el_fezzazi'));

            // Année actuelle
            $inscrit['7'] = new ProjetEleve();
            $inscrit['7']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('lucile_gasparini'));
            $inscrit['8'] = new ProjetEleve();
            $inscrit['8']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('mateo_sachet'));
            $inscrit['9'] = new ProjetEleve();
            $inscrit['9']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('nelson_melo'));
            $inscrit['10'] = new ProjetEleve();
            $inscrit['10']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('titouan_de_souza'));
            $inscrit['11'] = new ProjetEleve();
            $inscrit['11']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('claire_alves'));
            $inscrit['12'] = new ProjetEleve();
            $inscrit['12']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('nesrine_abada'));
            $inscrit['13'] = new ProjetEleve();
            $inscrit['13']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('emma_gausson'));
            $inscrit['14'] = new ProjetEleve();
            $inscrit['14']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('theatre'))
                ->setLyceen($this->getReference('louis_geoffroy'));


            // Inscription à focus

                // Année précédente

            $inscrit['15'] = new ProjetEleve();
            $inscrit['15']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('nesrine_abada'));
            $inscrit['16'] = new ProjetEleve();
            $inscrit['16']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('noemie_grapindor'));
            $inscrit['17'] = new ProjetEleve();
            $inscrit['17']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('arnaud_milome'));
            $inscrit['18'] = new ProjetEleve();
            $inscrit['18']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('leo_decoodt'));
            $inscrit['19'] = new ProjetEleve();
            $inscrit['19']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('karim_el_fezzazi'));
            $inscrit['20'] = new ProjetEleve();
            $inscrit['20']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('emma_gausson'));
            $inscrit['21'] = new ProjetEleve();
            $inscrit['21']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('arno_dubois'));
            $inscrit['22'] = new ProjetEleve();
            $inscrit['22']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('louis_geoffroy'));
            $inscrit['23'] = new ProjetEleve();
            $inscrit['23']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('maia_melina'));
            $inscrit['24'] = new ProjetEleve();
            $inscrit['24']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('focus'))
                ->setLyceen($this->getReference('claire_alves'));

                // Année actuelle

                    // Rien car inscriptions fermées

            // Inscriptions à GML

                // Année précédente
            
            $inscrit['25'] = new ProjetEleve();
            $inscrit['25']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('lauren_doucet'));
            $inscrit['26'] = new ProjetEleve();
            $inscrit['26']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('aude_ambrosini'));
            $inscrit['27'] = new ProjetEleve();
            $inscrit['27']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('noemie_grapindor'));
            $inscrit['28'] = new ProjetEleve();
            $inscrit['28']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('titouan_de_souza'));
            $inscrit['29'] = new ProjetEleve();
            $inscrit['29']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('arno_dubois'));
            $inscrit['30'] = new ProjetEleve();
            $inscrit['30']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('nelson_melo'));
            $inscrit['31'] = new ProjetEleve();
            $inscrit['31']->setAnneeScolaire($anneeP)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('louis_geoffroy'));


            // Année actuelle

            $inscrit['32'] = new ProjetEleve();
            $inscrit['32']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('nesrine_abada'));
            $inscrit['33'] = new ProjetEleve();
            $inscrit['33']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('mateo_sachet'));
            $inscrit['34'] = new ProjetEleve();
            $inscrit['34']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('arnaud_milome'));
            $inscrit['35'] = new ProjetEleve();
            $inscrit['35']->setAnneeScolaire($annee)
                ->setProjet($this->getReference('gml'))
                ->setLyceen($this->getReference('arno_dubois'));



            // Persist & références

            foreach($inscrit as $key => $value)
            {
                $manager->persist($value);
                $this->addReference('inscrit_'.$key, $value);
            }

            $manager->flush();

            

    }

    /**
    * {@inheritDoc()}
    */
    public function getDependencies()
    {
        return array(
            'CEC\MembreBundle\DataFixtures\ORM\LoadEleves',
            'CEC\SecteurProjetsBundle\DataFixtures\ORM\LoadProjets'
            );
    }
}