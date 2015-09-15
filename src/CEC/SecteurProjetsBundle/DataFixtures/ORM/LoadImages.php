<?php

namespace CEC\SecteurProjetsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\SecteurProjetsBundle\Entity\Image;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

class LoadImages extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        // On supprime les fichiers de toutes les activités
        $cheminDossierImages = __DIR__ . '/../../../../../web/uploads/images';
        try{
            $dossier = opendir($cheminDossierImages);
        }catch(\Exception $e)
        {
            mkdir(__DIR__ . '/../../../../../web/uploads/images', 0777, true);
            $dossier = opendir($cheminDossierImages);
        }
        while ($fichier = readdir($dossier)) {
            if ($fichier != '.' && $fichier != '..') unlink($cheminDossierImages . '/' . $fichier);
        }

        // On copie les fichiers d'exemple
        $cheminDossierFixtures = __DIR__ . '/../Documents';
        $image1Fichier = "image1.png";
        $image2Fichier = "image2.png";
        $image3Fichier = "image3.png";
        $image4Fichier = "image4.png";
        $image5Fichier = "image5.png";

        copy($cheminDossierFixtures . '/' . $image1Fichier, $cheminDossierImages . '/' . $image1Fichier);
        copy($cheminDossierFixtures . '/' . $image2Fichier, $cheminDossierImages . '/' . $image2Fichier);
        copy($cheminDossierFixtures . '/' . $image3Fichier, $cheminDossierImages . '/' . $image3Fichier);
        copy($cheminDossierFixtures . '/' . $image4Fichier, $cheminDossierImages . '/' . $image4Fichier);
        copy($cheminDossierFixtures . '/' . $image5Fichier, $cheminDossierImages . '/' . $image5Fichier);


        // Photos de l'album1
        $image1 = new Image();
        $image1->setAlt("Photo 1 de l'album 1")
            ->setLegende("Album 1 : Photo 1")
            ->setPath($image1Fichier)
            ->setAlbum($this->getReference('album1'));
        $image2 = new Image();
        $image2->setAlt("Photo 2 de l'album 1")
            ->setLegende("Album 1 : Photo 2")
            ->setPath($image3Fichier)
            ->setAlbum($this->getReference('album1'));
        $image3 = new Image();
        $image3->setAlt("Photo 3 de l'album 1")
            ->setLegende("Album 1 : Photo 3")
            ->setPath($image4Fichier)
            ->setAlbum($this->getReference('album1'));

        // Photos de l'album2
        $image4 = new Image();
        $image4->setAlt("Photo 1 de l'album 2")
            ->setLegende("Album 2 : Photo 1")
            ->setPath($image1Fichier)
            ->setAlbum($this->getReference('album2'));
        $image5 = new Image();
        $image5->setAlt("Photo 2 de l'album 2")
            ->setLegende("Album 2 : Photo 2")
            ->setPath($image2Fichier)
            ->setAlbum($this->getReference('album2'));
        $image6 = new Image();
        $image6->setAlt("Photo 3 de l'album 2")
            ->setLegende("Album 2 : Photo 3")
            ->setPath($image3Fichier)
            ->setAlbum($this->getReference('album2'));
        $image7 = new Image();
        $image7->setAlt("Photo 4 de l'album 2")
            ->setLegende("Album 2 : Photo 4")
            ->setPath($image5Fichier)
            ->setAlbum($this->getReference('album2'));

        // Photos de l'album 3
        $image8 = new Image();
        $image8->setAlt("Photo 1 de l'album 3")
            ->setLegende("Album 3 : Photo 1")
            ->setPath($image5Fichier)
            ->setAlbum($this->getReference('album3'));
        $image9 = new Image();
        $image9->setAlt("Photo 2 de l'album 3")
            ->setLegende("Album 3 : Photo 2")
            ->setPath($image3Fichier)
            ->setAlbum($this->getReference('album3'));
        $image10 = new Image();
        $image10->setAlt("Photo 3 de l'album 3")
            ->setLegende("Album 3 : Photo 3")
            ->setPath($image1Fichier)
            ->setAlbum($this->getReference('album3'));


        // Photos de l'album 4
        $image8 = new Image();
        $image8->setAlt("Photo 1 de l'album 4")
            ->setLegende("Album 4 : Photo 1")
            ->setPath($image1Fichier)
            ->setAlbum($this->getReference('album4'));
        $image8 = new Image();
        $image8->setAlt("Photo 2 de l'album 4")
            ->setLegende("Album 4 : Photo 2")
            ->setPath($image2Fichier)
            ->setAlbum($this->getReference('album4'));
        $image8 = new Image();
        $image8->setAlt("Photo 3 de l'album 4")
            ->setLegende("Album 4 : Photo 3")
            ->setPath($image3Fichier)
            ->setAlbum($this->getReference('album4'));
        $image8 = new Image();
        $image8->setAlt("Photo 4 de l'album 4")
            ->setLegende("Album 4 : Photo 4")
            ->setPath($image4Fichier)
            ->setAlbum($this->getReference('album4'));
        $image8 = new Image();
        $image8->setAlt("Photo 5 de l'album 4")
            ->setLegende("Album 4 : Photo 5")
            ->setPath($image5Fichier)
            ->setAlbum($this->getReference('album4'));
        
        
        $manager->persist($image1);
        $manager->persist($image2);
        $manager->persist($image3);
        $manager->persist($image4);
        $manager->persist($image5);
        $manager->persist($image6);
        $manager->persist($image7);
        $manager->persist($image8);

        $manager->flush();


    }

    /**
    * {@inheritDoc()}
    */
    public function getDependencies()
    {
        return array(
            'CEC\SecteurProjetsBundle\DataFixtures\ORM\LoadAlbums'
            );
    }
}