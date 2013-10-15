<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Lyceen;


class LoadLyceens extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tableauDesNomsGroupes = $this->tableauDesNomsGroupes();
        for ($i = 0; $i < 80; $i++) {
            $lyceen = $this->nouveauLyceenAleatoire();
            if (rand(0, 15) != 0) {
                $nomGroupe = $tableauDesNomsGroupes[rand(0, count($tableauDesNomsGroupes) - 1)];
                $lyceen->setGroupe($this->getReference($nomGroupe));
            }
            $manager->persist($lyceen);
        }
        
        ///// TODO: Créer quelques lycéens fixés en ajoutant les références pour les actis.
        
        $manager->flush();
    }
    
    /**
     * Crée et retourne un lycéen avec des informations aléatoires.
     *
     * @return Lycee Nouveau Lycéen.
     */
    public function nouveauLyceenAleatoire()
    {
        $prenom = $this->prenomAleatoire();
        $nom = $this->nomAleatoire();
        $adresse = rand(0, 1);
        $lyceen = $this->nouveauLyceen(
            $prenom,
            $nom,
            $this->telephoneAleatoire(),
            $this->emailAleatoire($prenom, $nom),
            $adresse ? $this->adresseAleatoire() : null,
            $adresse ? $this->codePostalAleatoire() : null,
            $adresse ? $this->villeAleatoire() : null,
            null,
            null,
            $this->telephoneAleatoire(false)
        );
        return $lyceen;
    }
    
    
    /**
     * Crée et retourne un nouveau lycéen avec les informations fournies.
     *
     * @param string $prenom
     * @param string $nom
     * @param string $telephone
     * @param string $email
     * @param string $adresse
     * @param string $codePostal
     * @param string $nomPere
     * @param string $nomMere
     * @param string $telephoneParent
     * @param string $commantaires
     * @return Lyceen Nouveau lycéen.
     */
    public function nouveauLyceen($prenom, $nom, 
        $telephone = null, $email = null, $adresse = null, $codePostal = null, $ville = null,
        $nomPere = null, $nomMere = null, $telephoneParent = null,
        $commentaires = null)
    {
        $lyceen = new Lyceen();
        $lyceen->setPrenom($prenom)
               ->setNom($nom)
               ->setTelephone($telephone)
               ->setEmail($email)
               ->setAdresse($adresse)
               ->setCodePostal($codePostal)
               ->setVille($ville)
               ->setNomPere($nomPere)
               ->setNomMere($nomMere)
               ->setTelephoneParent($telephoneParent)
               ->setCommentaires($commentaires);
        return $lyceen;
    }
    
    /**
     * Retourne un prénom aléatoire.
     *
     * @return string Prénom aléatoire.
     */
    public function prenomAleatoire()
    {
        $prenoms = array(
            'Amaryllys',
            'Pernille',
            'Canelle',
            'Cleore',
            'Ombeline',
            'Rihana',
            'Houria',
            'Illona',
            'Lonia',
            'Layana',
            'Taina',
            'Yola',
            'Aliette',
            'Guillemette',
            'Fanette',
            'Lorette',
            'Eoline',
            'Cyprienne',
            'Samuella',
            'Shaina',
            'Adélie',
            'April',
            'Aissa',
            'Sakina',
            'Sanae',
            'Sian',
            'Alana',
            'Louen',
            'Adria',
            'Auxane',
            
            'Enzo',
            'Bosco',
            'Renzo',
            'Dino',
            'Adriel',
            'Ferreol',
            'Antolin',
            'Antime',
            'Kyrian',
            'Teoman',
            'Lilyan',
            'Aurian',
            'Bastian',
            'Dohan',
            'Celian',
            'Timmy',
            'Calix',
            'Alexei',
            'Enrik',
            'Tilio',
            'Dael',
            'Isao',
            'Nori',
            'Tanis',
            'Jessim',
            'Kaan',
            'Aslan',
            'Aylan',
        );
        return $prenoms[rand(0, count($prenoms) - 1)];
    }
    
    /**
     * Retourne un nom de famille aléatoire.
     *
     * @return string Nom de famille aléatoire.
     */
    public function nomAleatoire()
    {
        $noms = array(
            'Martin',
            'Bernard',
            'Thomas',
            'Dubois',
            'Durand',
            'Robert',
            'Moreau',
            'Petit',
            'Simon',
            'Michel',
            'Leroy',
            'Laurent',
            'Lefebvre',
            'Bertrand',
            'Roux',
            'Legrand',
            'Garcia',
            'Lambert',
            'Bonnet',
            'Morel',
            'Girard',
            'Andrée',
            'Dupont',
            'Guerin',
            'Fournier',
            'Rousseau',
            'François',
            'Fontaine',
            'Mercier',
            'Roussel',
            'Bernier',
            'Boyer',
            'Blanc',
            'Henry',
            'Chevalier',
            'Masson',
            'Clément',
            'Perrin',
            'Lemaire',
            'Dumont',
            'Robin',
            'Barbier',
            'Blanchard',
            'Leroux',
            'Guyot',
            'Perrot',
            'Collin',
            'Jean',
            'Arnould',
            'Vasseur',
            'Deschamps',
            'Lecomte',
            'Benoît',
            'Berger',
            'Dupuy',
            'Leclerq',
            'Duval',
            'Rolland',
            'Marchand',
            'Jolly',
            'Lucas',
            'Schmitt',
            'Giraud',
            'Fabvre',
            'Gaillard',
            'Sanchez',
            'Lemoine',
            'Meyer',
            'Picard',
            'Le Gall',
            'Roy',
            'Bourgeois',
            'Lopez',
            'Prevost',
            'Faure',
            'Meunier',
            'Roche',
            'Vignal',
            'Durin',
            'Favier',
            'David',
            'Delorme',
            'Dufour',
            'Poisson',
            'Rougier',
            'Martins',
            'Favier',
            'Couturier',
            'Clermont',
            'Tournadre',
            'Eymard',
            'Authier',
            'Plessis',
            'Cormier',
            'Pasquier',
            'Metivier',
            'Metayer',
            'Barbier',
            'Benoist',
            'Pichon',
            'Nogues',
            'Veron',
            'Brossard',
        );
        return $noms[rand(0, count($noms) - 1)];
    }
    
    /**
     * Retourne une adresse email aléatoire construite à partir
     * du nom et du prénom.
     *
     * @param string $prenom
     * @param string $nom
     * @return string Adresse email aléatoire.
     */
    public function emailAleatoire($prenom, $nom)
    {
        $fournisseurs = array(
            'gmail',
            'hotmail',
            'laposte',
            'orange',
            'wanadoo',
            'numericable',
            'sfr',
            'free'
        );
        $extensions = array('fr', 'com');
        
        $email = '';
        $prenom = strtolower($prenom);
        $nom = strtolower($nom);
        switch (rand(1, 4)) {
            case 1:
                $email .= $prenom;
                break;
            case 2:
                $email .= substr($prenom, 0, 1);
                break;
            case 3:
                $email .= substr($prenom, 0, rand(0, strlen($prenom)));
                break;
        }
        switch (rand(1, 4)) {
            case 1:
                $email .= '.';
                break;
            case 2:
                $email .= '-';
                break;
            case 3:
                $email .= '_';
                break;
        }
        switch (rand(1, 2)) {
            case 1:
                $email .= $nom;
                break;
            case 2:
                $email .= substr($nom, 0, 1);
                break;
        }
        switch (rand(1, 2)) {
            case 1:
                $email .= rand(0, 9);
                $email .= rand(0, 9);
                break;
        }
        $email .= '@';
        $email .= $fournisseurs[rand(0, count($fournisseurs) - 1)];
        $email .= '.' . $extensions[rand(0, count($extensions) - 1)];
        
        return $email;
    }
    
    /**
     * Retourne une adresse postale aléatoire.
     *
     * @return string Adresse postale aléatoire.
     */
    public function adresseAleatoire()
    {
        $rues = array(
            'rue',
            'rue',
            'rue',
            'rue',
            'rue',
            'sentier',
            'boulevard',
            'boulevard',
            'impasse',
            'avenue',
            'avenue',
            'avenue',
            'cours',
            'place',
            'ruelle',
            'quai',
        );
        $nomsRues = array(
            'Adolphe-Julien',
            "d'Alger",
            "de l'Amiral de Coligny",
            "André Breton",
            "André Malraux",
            "des Arts",
            "Basse",
            "Bailleul",
            "Baillet",
            "du Bouloi",
            "Bertin-Poirer",
            "de Beaujolais",
            "Cambon",
            "des Capucines",
            "du Caroussel",
            "du Châtelet",
            "au Change",
            "Courtalon",
            "du Cygne",
            "Dauphine",
            "des Deux-Ecus",
            "Duphot",
            "de l'Echelle",
            "de l'Ecole",
            "Etienne-Marcel",
            "des Fermes",
            "Française",
            "de la Ferronnerie",
            "François Miterrand",
            "de la Grande Tuanderie",
            "des Halles",
            "de Harlay",
            "Hérold",
            "de l'Horloge",
            "Hulot",
            "des Jacobins",
            "du Louvre",
            "de la Lingère",
            "de Marengo",
            "de la Monnaie",
            "Montesquieu",
            "Montmartre",
            "Montorgueil",
            "des Moulins",
            "de l'Opéra",
            "de l'Oratoire",
            "Perrault",
            "du Pélican",
            "Pierre Lescot",
            "Potier",
            "des Prêcheurs",
            "des Pyramides",
            "Rambuteau",
            "de Richelieu",
            "du Roy",
            "du Roule",
            "Royal",
            "Sauval",
            "Saint-Florentin",
            "Saint-Michel",
            "des Tuileries",
            "de Valois",
            "Vendôme",
            "de Chartres",
            "du Jardin",
            "du Théâtre Français",
            "Basse",
            "de la Boucle",
            "du Cinéma",
            "de l'Orient-Express",
            "des Piliers",
            "des Bons-Vivants",
        );
        
        $adresse  = rand(1, 90);
        $adresse .= ', ';
        $adresse .= $rues[rand(0, count($rues) - 1)] . ' ';
        $adresse .= $nomsRues[rand(0, count($nomsRues) - 1)];
        return $adresse;
    }
    
    /**
     * Retourne un code postal aléatoire.
     *
     * @return string Code Postal aléatoire.
     */
    public function codePostalAleatoire()
    {
        $codePostal = rand(12, 78) . rand(0, 4) . rand(0, 9) . rand(0, 9);
        return $codePostal;
    }
    
    /**
     * Retourne un numéro de téléphone aléatoire.
     * Il s'agit d'un numéro de portable par défaut.
     *
     * @param bool $telephonePortable: true si on veut un portable.
     * @return string Numéro de téléphone aléatoire.
     */
    public function telephoneAleatoire($telephonePortable = true)
    {
        $indicatifPortable = array('06', '07');
        $indicatifFixe = array('01', '02', '03', '04', '05');
        
        $telephone = '';
        if ($telephonePortable) {
            $telephone .= $indicatifPortable[rand(0, count($indicatifPortable) - 1)];
        } else {
            $telephone .= $indicatifFixe[rand(0, count($indicatifFixe) - 1)];
        }
        $telephone .= ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99);
        return $telephone;
    }
    
    /**
     * Retourne une ville aléatoire.
     *
     * @return string Ville aléatoire.
     */
    public function villeAleatoire()
    {
        $villes = array(
            'Achiet le Grand',
            'Andouillé',
            'Désingrand',
            'Bèze',
            'Bezons',
            'Bosc-Bordel',
            'Conas',
            'Conchy-les-Pots',
            'Condom',
            'Conne-de-Labarde',
            'Couilly-Pont-aux-Dames',
            'Duranus',
            'Deux Verges',
            'Fourqueux',
            'Jouys-en-Josas',
            'La baume-de-Transit',
            'La queue-en-brie',
            'Piney',
            'Quistinic',
            'Saint Jean de Cuculles',
            'Saligos',
            'Salau',
            'Sotteville',
            'Sucé-sur-Erdre',
            'Sussat',
            'Trécon',
            'Trie-sur-Baïse',
            'Bourg-le-Roi',
            'Bourg-Madame',
            'Doulcon',
            'Triqueville',
            'Céré-la-Ronde',
        );
        return $villes[rand(0, count($villes) - 1)];
    }
    
    /**
     * Retourne un tableau contenant le nom des groupes de test.
     *
     * @return array Tableau des groupes de test.
     */
    public function tableauDesNomsGroupes()
    {
        return array(
            'tropajuste_comessa_secondes',
            'tropajuste_premieres',
            'comessa_premieres',
            'lavy_paleuparadhi_premieres',
            'lavy_paleuparadhi_terminales',
            'maphore_premieres',
            'maphore_terminales',
            'palhom_kipranlamaire_secondes',
            'palhom_kipranlamaire_premieres_terminales',
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\TutoratBundle\DataFixtures\ORM\LoadGroupes',
        );
    }
}
