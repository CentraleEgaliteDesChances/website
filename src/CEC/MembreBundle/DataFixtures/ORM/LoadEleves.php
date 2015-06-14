<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\MembreBundle\Entity\Eleve;

class LoadEleves extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        $nesrine_abada = $this->nouveauLyceenAleatoire("Nesrine", "Abada");
        $claire_alves = $this->nouveauLyceenAleatoire("Claire", "Alves");
        $aude_ambrosini = $this->nouveauLyceenAleatoire("Aude", "Ambrosini");
        $ines_chiandotto = $this->nouveauLyceenAleatoire("Ines", "Chiandotto");
        $titouan_de_souza = $this->nouveauLyceenAleatoire("Titouan", "De Souza");
        $leo_decoodt = $this->nouveauLyceenAleatoire("Léo", "Decoodt");
        $lauren_doucet = $this->nouveauLyceenAleatoire("Lauren", "Doucet");
        $maia_melina = $this->nouveauLyceenAleatoire("Maïa", "Mélina");
        $mateo_sachet = $this->nouveauLyceenAleatoire("Matéo", "Sachet");
        $nelson_melo = $this->nouveauLyceenAleatoire("Nelson", "Melo");
        $anna_michel = $this->nouveauLyceenAleatoire("Anna", "Michel");
        $arnaud_milome = $this->nouveauLyceenAleatoire("Arnaud", "Milome");
        $yanis_felix = $this->nouveauLyceenAleatoire("Yanis", "Felix");
        $louis_geoffroy = $this->nouveauLyceenAleatoire("Louis", "Geoffroy");
        $noemie_grapindor = $this->nouveauLyceenAleatoire("Noémie", "Grapindor");
        $emma_gausson = $this->nouveauLyceenAleatoire("Emma", "Gausson");
        $lucile_gasparini = $this->nouveauLyceenAleatoire("Lucile", "Gasparini");
        $mehdi_ferdoss = $this->nouveauLyceenAleatoire("Mehdi", "Ferdoss");
        $karim_el_fezzazi = $this->nouveauLyceenAleatoire("Karim", "El Fezzazi");
        $arno_dubois = $this->nouveauLyceenAleatoire("Arno", "Dubois");
        
        $manager->persist($nesrine_abada);
        $manager->persist($claire_alves);
        $manager->persist($aude_ambrosini);
        $manager->persist($ines_chiandotto);
        $manager->persist($titouan_de_souza);
        $manager->persist($leo_decoodt);
        $manager->persist($lauren_doucet);
        $manager->persist($maia_melina);
        $manager->persist($mateo_sachet);
        $manager->persist($nelson_melo);
        $manager->persist($anna_michel);
        $manager->persist($arnaud_milome);
        $manager->persist($yanis_felix);
        $manager->persist($louis_geoffroy);
        $manager->persist($noemie_grapindor);
        $manager->persist($emma_gausson);
        $manager->persist($lucile_gasparini);
        $manager->persist($mehdi_ferdoss);
        $manager->persist($karim_el_fezzazi);
        $manager->persist($arno_dubois);

        $manager->flush();

        $this->addReference('nesrine_abada', $nesrine_abada);
        $this->addReference('claire_alves', $claire_alves);
        $this->addReference('aude_ambrosini', $aude_ambrosini);
        $this->addReference('ines_chiandotto', $ines_chiandotto);
        $this->addReference('titouan_de_souza', $titouan_de_souza);
        $this->addReference('leo_decoodt', $leo_decoodt);
        $this->addReference('lauren_doucet', $lauren_doucet);
        $this->addReference('maia_melina', $maia_melina);
        $this->addReference('mateo_sachet', $mateo_sachet);
        $this->addReference('nelson_melo', $nelson_melo);
        $this->addReference('anna_michel', $anna_michel);
        $this->addReference('arnaud_milome', $arnaud_milome);
        $this->addReference('yanis_felix', $yanis_felix);
        $this->addReference('louis_geoffroy', $louis_geoffroy);
        $this->addReference('noemie_grapindor', $noemie_grapindor);
        $this->addReference('emma_gausson', $emma_gausson);
        $this->addReference('lucile_gasparini', $lucile_gasparini);
        $this->addReference('mehdi_ferdoss', $mehdi_ferdoss);
        $this->addReference('karim_el_fezzazi', $karim_el_fezzazi);
        $this->addReference('arno_dubois', $arno_dubois);
    }   
    
    /**
     * Crée et retourne un lycéen avec des informations aléatoires.
     *
     * @return Lycee Nouveau Lycéen.
     */
    public function nouveauLyceenAleatoire($prenom, $nom)
    {
        $prenom = $prenom;
        $nom = $nom;
        $adresse = rand(0, 1);
        $delegue = rand(0,10);
        $lyceen = $this->nouveauLyceen(
            $prenom,
            $nom,
            $this->telephoneAleatoire(),
            $this->emailAleatoire($prenom, $nom),
            $this->adresseAleatoire(),
            $this->codePostalAleatoire(),
            $this->villeAleatoire(),
            null,
            null,
            $this->telephoneAleatoire(false),
            new \DateTime(),
            $this->lyceeAleatoire(),
            $delegue
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
     * @param DateTime $dateNaiss
     * @return Lyceen Nouveau lycéen.
     */
    public function nouveauLyceen($prenom, $nom,
        $telephone = null, $email = null, $adresse = null, $codePostal = null, $ville = null,
        $nomPere = null, $nomMere = null, $telephoneParent = null, $dateNaiss = null, $lycee = null, $delegue = null)
    {

        $lyceen = new Eleve();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($lyceen);
        $mdp = $encoder->encodePassword('debug', $lyceen->getSalt());

        if($delegue == 0)
            $lyceeDelegue = $this->getReference($lycee);
        else
            $lyceeDelegue = null;

        $lyceen->setPrenom($prenom)
               ->setNom($nom)
               ->setTelephone($telephone)
               ->setTelephonePublic(false)
               ->setMail($email)
               ->setAdresse($adresse)
               ->setCodePostal($codePostal)
               ->setVille($ville)
               ->setNomPere($nomPere)
               ->setNomMere($nomMere)
               ->setTelephoneParent($telephoneParent)
               ->setDatenaiss($dateNaiss)
               ->setMotDePasse($mdp)
               ->setCheckMail(false)
               ->setLycee($this->getReference($lycee))
               ->setDelegue($lyceeDelegue);
        return $lyceen;
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
  
