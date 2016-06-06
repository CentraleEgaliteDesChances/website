<?php

namespace CEC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\ActiviteBundle\Entity\Document;
use CEC\ActiviteBundle\Entity\QuizzActu;

class LoadQuizzActus extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // On supprime les fichiers de toutes les activités
        $cheminDossierQuizzActus = __DIR__ . '/../../../../../web/uploads/quizzActus';
        $dossier = opendir($cheminDossierQuizzActus);
        while ($fichier = readdir($dossier)) {
            if ($fichier != '.' && $fichier != '..') unlink($cheminDossierQuizzActus . '/' . $fichier);
        }

        // On copie les fichiers d'exemple
        $cheminDossierFixtures = __DIR__ . '/../Documents';
        $pdfFixture = "data-fixture.pdf";

        copy($cheminDossierFixtures . '/' . $pdfFixture, $cheminDossierQuizzActus . '/' . $pdfFixture);

            
        $maintenant = new \DateTime();

        $quizz1 = new QuizzActu();
        $quizz1->setNomFichierPDF($pdfFixture)
                ->setAuteur($this->getReference('pol_maire'))
                ->setSemaine($maintenant->modify(($maintenant->format('w') === '0') ? 'monday last week' : 'monday this week'))
                ->setCommentaire('Quizz Actu bidon mais très intéressant');

        $quizz2 = new QuizzActu();
        $quizz2->setNomFichierPDF($pdfFixture)
                ->setCommentaire('Ajout de tel et tel éléments, permettant de rallonger la durée de l\'activité.')
                ->setAuteur($this->getReference('helene_sicsic'))
                ->setSemaine($maintenant->modify(($maintenant->format('w') === '0')? 'monday last week' : 'monday this week'));
        
        $manager->persist($quizz1);
        $manager->persist($quizz2);
        $manager->flush();

        $this->addReference('quizz1', $quizz1);
        $this->addReference('quizz2', $quizz2);

    }
    
    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\MembreBundle\DataFixtures\ORM\LoadMembres'
        );
    }
}
