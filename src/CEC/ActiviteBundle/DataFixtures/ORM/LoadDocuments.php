<?php

namespace CEC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\ActiviteBundle\Entity\Document;

class LoadDocuments extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // On supprime les fichiers de toutes les activités
        $cheminDossierDocuments = __DIR__ . '/../../../../../web/uploads/documents';
        try{
            $dossier = opendir($cheminDossierDocuments);
        }catch(\Exception $e)
        {
            mkdir(__DIR__ . '/../../../../../web/uploads/documents', 0777, true);
            $dossier = opendir($cheminDossierDocuments);
        }
        while ($fichier = readdir($dossier)) {
            if ($fichier != '.' && $fichier != '..') unlink($cheminDossierDocuments . '/' . $fichier);
        }

        // On copie les fichiers d'exemple
        $cheminDossierFixtures = __DIR__ . '/../Documents';
        $pdfFixture = "data-fixture.pdf";
        $wordFixture = "data-fixture.doc";
        copy($cheminDossierFixtures . '/' . $wordFixture, $cheminDossierDocuments . '/' . $wordFixture);
        copy($cheminDossierFixtures . '/' . $pdfFixture, $cheminDossierDocuments . '/' . $pdfFixture);

            
        $maintenant = new \DateTime();
    
        $acti1v1 = new Document();
        $acti1v1->setNomFichierPDF($pdfFixture)
                ->setNomFichierOriginal($wordFixture)
                ->setDescription('Téléchargement de la première version.')
                ->setAuteur($this->getReference('pol_maire'))
                ->setActivite($this->getReference('acti1'));
        
        $acti1v2 = new Document();
        $acti1v2->setNomFichierPDF($pdfFixture)
                ->setNomFichierOriginal($wordFixture)
                ->setDescription('Ajout de tel et tel éléments, permettant de rallonger la durée de l\'activité.')
                ->setAuteur($this->getReference('helene_sicsic'))
                ->setActivite($this->getReference('acti1'));
                
        $acti2v1 = new Document();
        $acti2v1->setNomFichierPDF($pdfFixture)
              ->setNomFichierOriginal($wordFixture)
              ->setDateCreation($maintenant)
              ->setDateModification($maintenant)
              ->setAuteur($this->getReference('pol_maire'))
              ->setActivite($this->getReference('acti2'));
        
        $manager->persist($acti1v1);
        $manager->persist($acti1v2);
        $manager->persist($acti2v1);
        $manager->flush();
        
        $this->addReference('acti1v1', $acti1v1);
        $this->addReference('acti1v2', $acti1v2);
        $this->addReference('acti2v1', $acti2v1);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\MembreBundle\DataFixtures\ORM\LoadMembres',
            'CEC\ActiviteBundle\DataFixtures\ORM\LoadActivites',
        );
    }
}
