<?php

namespace CEC\SecteurSortiesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\SecteurSortiesBundle\Entity\Sortie;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class LoadSorties extends AbstractFixture
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

        // Sorties de l'année dernière

        $sortie1 = new Sortie();
        $sortie1->setNom("Palais de la Découverte")
            ->setAdresse("Palais de la Découverte")
            ->setAnneeScolaire($anneeScolaireP)
            ->setDateSortie(new \DateTime(($anneeP-1).'-11-22'))
            ->setHeureDebut(new \DateTime(($anneeP-1).'-11-22T09:30:00'))
            ->setHeureFin(new \DateTime(($anneeP-1).'-11-22T13:00:00'))
            ->setDescription("Visite obligatoire du palais de la découverte")
            ->setNbLyceens(6)
            ->setNbTuteurs(5)
            ->setCommentaire('Belle journée')
            ->setPrix(150)
            ->setOkCR(true);
        $sortie2 = new Sortie();
        $sortie2->setNom("Journée des Cordées")
            ->setAdresse("Centrale")
            ->setAnneeScolaire($anneeScolaireP)
            ->setDateSortie(new \DateTime($anneeP.'-01-31'))
            ->setHeureDebut(new \DateTime($anneeP.'-01-31T09:30:00'))
            ->setHeureFin(new \DateTime($anneeP.'-01-31T17:00:00'))
            ->setDescription("Journée nationale sur le thème de l'orientation")
            ->setNbLyceens(5)
            ->setNbTuteurs(20)
            ->setCommentaire("Magnifique journée")
            ->setPrix(400)
            ->setOkCR(true);
        $sortie3 = new Sortie();
        $sortie3->setNom("Exposition Ghibli")
            ->setAdresse("Grand Palais")
            ->setAnneeScolaire($anneeScolaireP)
            ->setDateSortie(new \DateTime($anneeP.'-01-18'))
            ->setHeureDebut(new \DateTime($anneeP.'-01-18T14:30:00'))
            ->setHeureFin(new \DateTime($anneeP.'-01-18T16:30:00'))
            ->setDescription("Exposition sur les studios Ghibli")
            ->setPlaces(5)
            ->setNbLyceens(4)
            ->setNbTuteurs(2)
            ->setCommentaire('Il y avait un absent !')
            ->setPrix(125)
            ->setOkCR(true);
        $sortie4 = new Sortie();
        $sortie4->setNom("Derniers coups de ciseaux")
            ->setAdresse("Paris")
            ->setAnneeScolaire($anneeScolaireP)
            ->setDateSortie(new \DateTime($anneeP.'-03-28'))
            ->setHeureDebut(new \DateTime($anneeP.'-03-28T15:30:00'))
            ->setHeureFin(new \DateTime($anneeP.'-03-28T18:00:00'))
            ->setDescription("Pièce de théâtre participative")
            ->setPlaces(8)
            ->setNbLyceens(8)
            ->setNbTuteurs(3)
            ->setCommentaire('Un absent mais rattrapé par la liste d\'attente')
            ->setPrix(240)
            ->setOkCR(true);
        $sortie5 = new Sortie();
        $sortie5->setNom("Journée de Clôture")
            ->setAdresse("Centrale")
            ->setAnneeScolaire($anneeScolaireP)
            ->setDateSortie(new \DateTime($anneeP.'-06-04'))
            ->setHeureDebut(new \DateTime($anneeP.'-06-04T09:30:00'))
            ->setHeureFin(new \DateTime($anneeP.'-06-04T17:00:00'))
            ->setDescription("Journée de clôture de l'année avec différents ateliers")
            ->setNbLyceens(5)
            ->setNbTuteurs(23)
            ->setCommentaire("Superbe journée")
            ->setPrix(1000)
            ->setOkCR(true);
        $sortie6 = new Sortie();
        $sortie6->setNom("Stade de France")
            ->setAdresse("Stade de France")
            ->setAnneeScolaire($anneeScolaireP)
            ->setDateSortie(new \DateTime($anneeP.'-05-16'))
            ->setHeureDebut(new \DateTime($anneeP.'-05-16T15:00:00'))
            ->setHeureFin(new \DateTime($anneeP.'-05-16T17:00:00'))
            ->setDescription("Visite du mythique Stade de France")
            ->setPlaces(1)
            ->setNbLyceens(1)
            ->setNbTuteurs(3)
            ->setCommentaire("Trois tuteurs pour un lycéen ?")
            ->setPrix(100)
            ->setOkCR(true);

        // Sorties de l'année actuelle

        $sortie7 = new Sortie();
        $sortie7->setNom("Palais de la Découverte")
            ->setAdresse("Palais de la Découverte")
            ->setAnneeScolaire($anneeScolaireA)
            ->setDateSortie(new \DateTime(($anneeA-1).'-11-22'))
            ->setHeureDebut(new \DateTime(($anneeA-1).'-11-22T09:30:00'))
            ->setHeureFin(new \DateTime(($anneeA-1).'-11-22T13:00:00'))
            ->setDescription("Visite obligatoire du palais de la découverte")
            ->setNbLyceens(5)
            ->setNbTuteurs(4)
            ->setCommentaire("Encore meilleure journée")
            ->setPrix(200)
            ->setOkCR(true);
        $sortie8 = new Sortie();
        $sortie8->setNom("Journée des Cordées")
            ->setAdresse("Centrale")
            ->setAnneeScolaire($anneeScolaireA)
            ->setDateSortie(new \DateTime($anneeA.'-01-31'))
            ->setHeureDebut(new \DateTime($anneeA.'-01-31T09:30:00'))
            ->setHeureFin(new \DateTime($anneeA.'-01-31T17:00:00'))
            ->setDescription("Journée nationale sur le thème de l'orientation");
        $sortie9 = new Sortie();
        $sortie9->setNom("Cité des sciences et de l'industrie")
            ->setAdresse("La Villette")
            ->setAnneeScolaire($anneeScolaireA)
            ->setDateSortie(new \DateTime($anneeA.'-01-18'))
            ->setHeureDebut(new \DateTime($anneeA.'-01-18T14:30:00'))
            ->setHeureFin(new \DateTime($anneeA.'-01-18T16:30:00'))
            ->setDescription("Visite de la cité des sciences")
            ->setPlaces(5);
            // Sortie au lendemain du test pour toujours avoir une sortie dans la liste à laquelle s'inscrire sur liste d'attente

            $lendemain = date('Y-m-d');
            $lendemain = new \DateTime($lendemain);
            $lendemain->add(new \DateInterval('P1D'));

        $sortie10 = new Sortie();
        $sortie10->setNom("Soirée de lancement Destination Prépa")
            ->setAdresse("Centrale")
            ->setAnneeScolaire($anneeScolaireA)
            ->setDateSortie($lendemain)
            ->setHeureDebut($lendemain->add(new \DateInterval('PT15H30M')))
            ->setHeureFin($lendemain->add(new \DateInterval('PT18H')))
            ->setDescription("Lancement du documentaire NX sur la prépa")
            ->setPlaces(3);
            // Sortie au sur-lendemain du test pour toujours avoir une sortie libre à laquelle s'inscrire

            $surlendemain = date('Y-m-d');
            $surlendemain = new \DateTime($surlendemain);
            $surlendemain->add(new \DateInterval('P2D'));

        $sortie11 = new Sortie();
        $sortie11->setNom("Journée de Clôture")
            ->setAdresse("Centrale")
            ->setAnneeScolaire($anneeScolaireA)
            ->setDateSortie($surlendemain)
            ->setHeureDebut($surlendemain->add(new \DateInterval('PT9H30M')))
            ->setHeureFin($surlendemain->add(new \DateInterval('PT17H')))
            ->setDescription("Journée de clôture de l'année avec différents ateliers");

        $manager->persist($sortie1);
        $manager->persist($sortie2);
        $manager->persist($sortie3);
        $manager->persist($sortie4);
        $manager->persist($sortie5);
        $manager->persist($sortie6);
        $manager->persist($sortie7);
        $manager->persist($sortie8);
        $manager->persist($sortie9);
        $manager->persist($sortie10);
        $manager->persist($sortie11);

        $manager->flush();

        $this->addReference('sortie1', $sortie1);
        $this->addReference('sortie2', $sortie2);
        $this->addReference('sortie3', $sortie3);
        $this->addReference('sortie4', $sortie4);
        $this->addReference('sortie5', $sortie5);
        $this->addReference('sortie6', $sortie6);
        $this->addReference('sortie7', $sortie7);
        $this->addReference('sortie8', $sortie8);
        $this->addReference('sortie9', $sortie9);
        $this->addReference('sortie10', $sortie10);
        $this->addReference('sortie11', $sortie11);

    }
}