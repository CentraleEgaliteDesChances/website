<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\MembreBundle\Entity\Professeur;

class LoadProfesseurs extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        $marie_pierre_abada = $this->nouveauProfAleatoire("Marie-Pierre", "Carlotti");
        $emmanuel_alves = $this->nouveauProfAleatoire("Emmanuel", "Deloges");
        $marie_ambrosini = $this->nouveauProfAleatoire("Marie", "Geider");
        $jacquie_guichard = $this->nouveauProfAleatoire("Jacqueline", "Guichard");
        $adele_leclere = $this->nouveauProfAleatoire("Adèle", "Leclere");
        $catherine_mourette = $this->nouveauProfAleatoire("Catherine", "Mourette");
        $sidonie_richon = $this->nouveauProfAleatoire("Sidonie", "Richon");
        $joelle_sportiello = $this->nouveauProfAleatoire("Joëlle", "Sportiello");
        $monique_thiollet = $this->nouveauProfAleatoire("Monique", "Thiollet");
        $erwan_leon = $this->nouveauProfAleatoire("Erwan", "Léon");
        $pierrick_madinier = $this->nouveauProfAleatoire("Pierrick", "Madinier");
        $thierry_merlet = $this->nouveauProfAleatoire("Thierry", "Merlet");



        $manager->persist($marie_pierre_abada);
        $manager->persist($emmanuel_alves);
        $manager->persist($marie_ambrosini);
        $manager->persist($jacquie_guichard);
        $manager->persist($adele_leclere);
        $manager->persist($catherine_mourette);
        $manager->persist($sidonie_richon);
        $manager->persist($joelle_sportiello);
        $manager->persist($monique_thiollet);
        $manager->persist($erwan_leon);
        $manager->persist($pierrick_madinier);
        $manager->persist($thierry_merlet);

        $manager->flush();

        $this->addReference('marie_pierre_abada', $marie_pierre_abada);
        $this->addReference('emmanuel_alves', $emmanuel_alves);
        $this->addReference('marie_ambrosini', $marie_ambrosini);
        $this->addReference('jacquie_guichard', $jacquie_guichard);
        $this->addReference('adele_leclere', $adele_leclere);
        $this->addReference('catherine_mourette', $catherine_mourette);
        $this->addReference('sidonie_richon', $sidonie_richon);
        $this->addReference('joelle_sportiello', $joelle_sportiello);
        $this->addReference('monique_thiollet', $monique_thiollet);
        $this->addReference('erwan_leon', $erwan_leon);
        $this->addReference('pierrick_madinier', $pierrick_madinier);
        $this->addReference('thierry_merlet', $thierry_merlet);
    }

    /**
     * Crée et retourne un professeur avec des informations aléatoires.
     *
     * @return Professeur Nouveau professeur
     */
    public function nouveauProfAleatoire($prenom, $nom)
    {

        $adresse = rand(0, 1);
        $professeur = $this->nouveauProfesseur(
            $prenom,
            $nom,
            $this->telephoneAleatoire(false),
            $this->telephoneAleatoire(),
            $this->emailAleatoire($prenom, $nom),
            $this->lyceeAleatoire(),
            $this->roleAleatoire(),
            rand(0,10)
        );
        return $professeur;
    }


    /**
     * Crée et retourne un nouveau lycéen avec les informations fournies.
     *
     * @param string $prenom
     * @param string $nom
     * @param string $telephone fixe
     * @param string $portable
     * @param string $lycee
     * @param string $role
     * @return Professeur Nouveau professeur
     */
    public function nouveauProfesseur($prenom, $nom,
                $telephone = null, $portable = null, $email = null, $lycee = null, $role = null, $referent = null)
    {

        $professeur = new Professeur();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($professeur);
        $mdp = $encoder->encodePassword('debug', $professeur->getSalt());

        if($referent == 0)
            $lyceeReferent = $this->getReference($lycee);
        else
            $lyceeReferent = null;

        $professeur->setPrenom($prenom)
               ->setNom($nom)
               ->setTelephoneFixe($telephone)
               ->setTelephonePortable($portable)
               ->setMail($email)
               ->setLycee($this->getReference($lycee))
               ->setRole($role)
               ->setReferent($lyceeReferent)
               ->setMotDePasse($mdp)
               ->setCheckMail(false)
                ->setUsername($prenom.$nom);
        return $professeur;
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
    * Retourne un rôle aléatoire
    * @return string Rôle aléatoire.
    */
    public function roleAleatoire()
    {
        $roles = ['Enseignant', 'Proviseur', 'Proviseur Adjoint', 'Conseiller Principal d\'Education'];
        return $roles[rand(0, count($roles) -1)];
    }

    /**
    * Retourne la référence d'un lycée source
    */
    public function lyceeAleatoire()
    {
        $lycees = $this->tableauDesNomsLycees();

        return $lycees[rand(0, count($lycees) -1)];
    }

    /**
     * Retourne un tableau contenant le nom des groupes de test.
     *
     * @return array Tableau des groupes de test.
     */
    public function tableauDesNomsLycees()
    {
        return array(
            'tropajuste',
            'comessa',
            'lavy_paleuparadhi',
            'maphore',
            'palhom_kipranlamaire'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies() {
        return array(
            'CEC\TutoratBundle\DataFixtures\ORM\LoadLycees',
        );
    }
}
  