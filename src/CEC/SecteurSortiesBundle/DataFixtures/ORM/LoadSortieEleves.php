<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\SecteurSortiesBundle\Entity\SortieEleve;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class LoadSortieEleves extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $anneeA = date('Y');
        $anneeScolaireA = new AnneeScolaire($anneeA -1);
        $anneeP = $anneeA -1;
        $anneeScolaireP = new AnneeScolaire($anneeP-1);

        // Tableau des participations
        $part = array();

        // Participation aux sorties l'année dernière

            // Palais de la découverte => Absence d'un élève
        $part[1] = new SortieEleve();
        $part[1]->setSortie($this->getReference('sortie1'))
            ->setLyceen($this->getReference('ines_chiandotto'));
        $part[2] = new SortieEleve();
        $part[2]->setSortie($this->getReference('sortie1'))
            ->setLyceen($this->getReference('lucile_gasparini'));
        $part[3] = new SortieEleve();
        $part[3]->setSortie($this->getReference('sortie1'))
            ->setLyceen($this->getReference('karim_el_fezzazi'));
        $part[4] = new SortieEleve();
        $part[4]->setSortie($this->getReference('sortie1'))
            ->setLyceen($this->getReference('leo_decoodt'));
        $part[5] = new SortieEleve();
        $part[5]->setSortie($this->getReference('sortie1'))
            ->setLyceen($this->getReference('arno_dubois'));
        $part[6] = new SortieEleve();
        $part[6]->setSortie($this->getReference('sortie1'))
            ->setLyceen($this->getReference('yanis_felix'))
            ->setPresence(false);

            // Journée des Cordées
        $part[7] = new SortieEleve();
        $part[7]->setSortie($this->getReference('sortie2'))
            ->setLyceen($this->getReference('louis_geoffroy'));
        $part[8] = new SortieEleve();
        $part[8]->setSortie($this->getReference('sortie2'))
            ->setLyceen($this->getReference('mateo_sachet'));
        $part[9] = new SortieEleve();
        $part[9]->setSortie($this->getReference('sortie2'))
            ->setLyceen($this->getReference('claire_alves'));
        $part[10] = new SortieEleve();
        $part[10]->setSortie($this->getReference('sortie2'))
            ->setLyceen($this->getReference('nesrine_abada'));

            //Expo Ghibli => Absence d'un élève et pas de remplacement depuis la liste d'attente
        $part[11] = new SortieEleve();
        $part[11]->setSortie($this->getReference('sortie3'))
            ->setLyceen($this->getReference('lauren_doucet'));
        $part[12] = new SortieEleve();
        $part[12]->setSortie($this->getReference('sortie3'))
            ->setLyceen($this->getReference('mehdi_ferdoss'))
            ->setPresence(false);
        $part[13] = new SortieEleve();
        $part[13]->setSortie($this->getReference('sortie3'))
            ->setLyceen($this->getReference('arno_dubois'));
        $part[14] = new SortieEleve();
        $part[14]->setSortie($this->getReference('sortie3'))
            ->setLyceen($this->getReference('emma_gausson'));
        $part[15] = new SortieEleve();
        $part[15]->setSortie($this->getReference('sortie3'))
            ->setLyceen($this->getReference('yanis_felix'));
        $part[16] = new SortieEleve();
        $part[16]->setSortie($this->getReference('sortie3'))
            ->setLyceen($this->getReference('anna_michel'))
            ->setListeAttente(1)
            ->setPresence(false);

            // Derniers coups de ciseaux => Liste d'attente un peu remplie
        $part[17] = new SortieEleve();
        $part[17]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('nesrine_abada'));
        $part[18] = new SortieEleve();
        $part[18]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('claire_alves'));
        $part[19] = new SortieEleve();
        $part[19]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('leo_decoodt'));
        $part[20] = new SortieEleve();
        $part[20]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('titouan_de_souza'));
        $part[21] = new SortieEleve();
        $part[21]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('mateo_sachet'));
        $part[22] = new SortieEleve();
        $part[22]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('emma_gausson'));
        $part[23] = new SortieEleve();
        $part[23]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('karim_el_fezzazi'));
        $part[24] = new SortieEleve();
        $part[24]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('arnaud_milome'));
        $part[25] = new SortieEleve();
        $part[25]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('yanis_felix'))
            ->setListeAttente(1)
            ->setPresence(false);
        $part[26] = new SortieEleve();
        $part[26]->setSortie($this->getReference('sortie4'))
            ->setLyceen($this->getReference('nelson_melo'))
            ->setListeAttente(2)
            ->setPresence(false);

            //Journée de Clôture
        $part[27] = new SortieEleve();
        $part[27]->setSortie($this->getReference('sortie5'))
            ->setLyceen($this->getReference('claire_alves'));
        $part[28] = new SortieEleve();
        $part[28]->setSortie($this->getReference('sortie5'))
            ->setLyceen($this->getReference('anna_michel'));
        $part[29] = new SortieEleve();
        $part[29]->setSortie($this->getReference('sortie5'))
            ->setLyceen($this->getReference('ines_chiandotto'));
        $part[30] = new SortieEleve();
        $part[30]->setSortie($this->getReference('sortie5'))
            ->setLyceen($this->getReference('aude_ambrosini'));
        $part[31] = new SortieEleve();
        $part[31]->setSortie($this->getReference('sortie5'))
            ->setLyceen($this->getReference('titouan_de_souza'));

            //Stade de France => Liste d'attente pleine
        $part[32] = new SortieEleve();
        $part[32]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('aude_ambrosini'));
        $part[33] = new SortieEleve();
        $part[33]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('leo_decoodt'))
            ->setListeAttente(1)
            ->setPresence(false);
        $part[34] = new SortieEleve();
        $part[34]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('nelson_melo'))
            ->setListeAttente(2)
            ->setPresence(false);
        $part[35] = new SortieEleve();
        $part[35]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('mateo_sachet'))
            ->setListeAttente(3)
            ->setPresence(false);
        $part[36] = new SortieEleve();
        $part[36]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('arno_dubois'))
            ->setListeAttente(4)
            ->setPresence(false);
        $part[37] = new SortieEleve();
        $part[37]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('arnaud_milome'))
            ->setListeAttente(5)
            ->setPresence(false);
        $part[38] = new SortieEleve();
        $part[38]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('lauren_doucet'))
            ->setListeAttente(6)
            ->setPresence(false);
        $part[39] = new SortieEleve();
        $part[39]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('claire_alves'))
            ->setListeAttente(7)
            ->setPresence(false);
        $part[40] = new SortieEleve();
        $part[40]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('maia_melina'))
            ->setListeAttente(8)
            ->setPresence(false);
        $part[41] = new SortieEleve();
        $part[41]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('lucile_gasparini'))
            ->setListeAttente(9)
            ->setPresence(false);
        $part[42] = new SortieEleve();
        $part[42]->setSortie($this->getReference('sortie6'))
            ->setLyceen($this->getReference('mehdi_ferdoss'))
            ->setListeAttente(10)
            ->setPresence(false);

        // Sorties de l'année actuelle

            // Palais de la découverte
        $part[43] = new SortieEleve();
        $part[43]->setSortie($this->getReference('sortie7'))
            ->setLyceen($this->getReference('nesrine_abada'));
        $part[44] = new SortieEleve();
        $part[44]->setSortie($this->getReference('sortie7'))
            ->setLyceen($this->getReference('aude_ambrosini'));
        $part[45] = new SortieEleve();
        $part[45]->setSortie($this->getReference('sortie7'))
            ->setLyceen($this->getReference('yanis_felix'));
        $part[46] = new SortieEleve();
        $part[46]->setSortie($this->getReference('sortie7'))
            ->setLyceen($this->getReference('arno_dubois'));
        $part[47] = new SortieEleve();
        $part[47]->setSortie($this->getReference('sortie7'))
            ->setLyceen($this->getReference('arnaud_milome'));

            //Journée des Cordées
        $part[48] = new SortieEleve();
        $part[48]->setSortie($this->getReference('sortie8'))
            ->setLyceen($this->getReference('mateo_sachet'));
        $part[49] = new SortieEleve();
        $part[49]->setSortie($this->getReference('sortie8'))
            ->setLyceen($this->getReference('arnaud_milome'));
        $part[50] = new SortieEleve();
        $part[50]->setSortie($this->getReference('sortie8'))
            ->setLyceen($this->getReference('anna_michel'));

            // Destination Prépa => test inscription sur liste d'attente
        $part[51] = new SortieEleve();
        $part[51]->setSortie($this->getReference('sortie10'))
            ->setLyceen($this->getReference('anna_michel'));
        $part[52] = new SortieEleve();
        $part[52]->setSortie($this->getReference('sortie10'))
            ->setLyceen($this->getReference('ines_chiandotto'));
        $part[53] = new SortieEleve();
        $part[53]->setSortie($this->getReference('sortie10'))
            ->setLyceen($this->getReference('lauren_doucet'));
        $part[54] = new SortieEleve();
        $part[54]->setSortie($this->getReference('sortie10'))
            ->setLyceen($this->getReference('leo_decoodt'))
            ->setListeAttente(1)
            ->setPresence(false);
        $part[55] = new SortieEleve();
        $part[55]->setSortie($this->getReference('sortie10'))
            ->setLyceen($this->getReference('titouan_de_souza'))
            ->setListeAttente(2)
            ->setPresence(false);

        foreach($part as $participation)
        {
            $manager->persist($participation);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\MembreBundle\DataFixtures\ORM\LoadEleves',
            'CEC\SecteurSortiesBundle\DataFixtures\ORM\LoadSorties'
        );
    }
}