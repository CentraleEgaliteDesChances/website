<?php

namespace CEC\SecteurProjetsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\SecteurProjetsBundle\Entity\Dossier;

class LoadDossiers extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // On supprime les fichiers de toutes les activités
        $cheminDossierDossiers = __DIR__ . '/../../../../../web/uploads/dossiers';
        try{
            $dossier = opendir($cheminDossierDossiers);
        }catch(\Exception $e)
        {
            mkdir(__DIR__ . '/../../../../../web/uploads/dossiers', 0777, true);
            $dossier = opendir($cheminDossierDossiers);
        }
        while ($fichier = readdir($dossier)) {
            if ($fichier != '.' && $fichier != '..') unlink($cheminDossierDossiers . '/' . $fichier);
        }

        // On copie les fichiers d'exemple
        $cheminDossierFixtures = __DIR__ . '/../Documents';
        $pdfFixture = "data-fixture.pdf";
        copy($cheminDossierFixtures . '/' . $pdfFixture, $cheminDossierDossiers . '/' . $pdfFixture);

            
        $maintenant = new \DateTime();
    
        $dossier1 = new Dossier();
        $dossier1->setNom($pdfFixture)
            ->setPath('data-fixture.pdf');
        
        $dossier2 = new Dossier();
        $dossier2->setNom($pdfFixture)
            ->setPath('data-fixture.pdf');
        
        $manager->persist($dossier1);
        $manager->persist($dossier2);

        $manager->flush();
        
        $this->addReference('dossier1', $dossier1);
        $this->addReference('dossier2', $dossier2);
    }
    
}
