<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use CEC\MembreBundle\Entity\ParentEleve;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadParents extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        $alice_abada=$this->nouveauParentAleatoire('Alice','Abada','nesrine_abada');
        $christophe_alves=$this->nouveauParentAleatoire('Christophe','Alves','claire_alves');
        $jean_ambrosini=$this->nouveauParentAleatoire('Jean','Ambrosini','aude_ambrosini');
        $marc_chiandotto=$this->nouveauParentAleatoire('Marc','Chiandotto','ines_chiandotto');
        $isabelle_de_souza=$this->nouveauParentAleatoire('Isabelle','De Souza','titouan_de_souza');
        $julien_decoodt=$this->nouveauParentAleatoire('Julien','Decoodt','leo_decoodt');
        $martine_doucet=$this->nouveauParentAleatoire('Martine','Doucet','lauren_doucet');
        $geraldine_melina=$this->nouveauParentAleatoire('Géraldine','Melina','maia_melina');
        $serge_sachet=$this->nouveauParentAleatoire('Serge','Sachet','mateo_sachet');
        $philippe_melo=$this->nouveauParentAleatoire('Philippe','Melo','nelson_melo');
        $herve_michel=$this->nouveauParentAleatoire('Hervé','Michel','anna_michel');
        $michelle_milome=$this->nouveauParentAleatoire('Michelle','Milome','arnaud_milome');
        $matthieu_felix=$this->nouveauParentAleatoire('Matthieu','Felix','yanis_felix');
        $laurent_geoffroy=$this->nouveauParentAleatoire('Laurent','Geoffroy','louis_geoffroy');
        $laure_grapindor=$this->nouveauParentAleatoire('Laure','Grapindor','noemie_grapindor');
        $anna_gausson=$this->nouveauParentAleatoire('Anna','Gausson','emma_gausson');
        $victoire_gasparini=$this->nouveauParentAleatoire('Victoire','Gasparini','lucile_gasparini');
        $jordan_ferdoss=$this->nouveauParentAleatoire('Jordan','Ferdoss','mehdi_ferdoss');
        $henry_el_fezzazi=$this->nouveauParentAleatoire('Henry','El Fezzazi','karim_el_fezzazi');
        $mickael_dubois=$this->nouveauParentAleatoire('Mickael','Dubois','arno_dubois');
        
        
        $manager->persist($alice_abada);
        $manager->persist($christophe_alves);
        $manager->persist($jean_ambrosini);
        $manager->persist($marc_chiandotto);
        $manager->persist($isabelle_de_souza);
        $manager->persist($julien_decoodt);
        $manager->persist($martine_doucet);
        $manager->persist($geraldine_melina);
        $manager->persist($serge_sachet);
        $manager->persist($philippe_melo);
        $manager->persist($herve_michel);
        $manager->persist($michelle_milome);
        $manager->persist($matthieu_felix);
        $manager->persist($laurent_geoffroy);
        $manager->persist($laure_grapindor);
        $manager->persist($anna_gausson);
        $manager->persist($victoire_gasparini);
        $manager->persist($jordan_ferdoss);
        $manager->persist($henry_el_fezzazi);
        $manager->persist($mickael_dubois);
        

        $manager->flush();

        $this->addReference('alice_abada',$alice_abada);
        $this->addReference('christophe_alves',$christophe_alves);
        $this->addReference('$jean_ambrosini',$jean_ambrosini);
        $this->addReference('marc_chiandotto',$marc_chiandotto);
        $this->addReference('isabelle_de_souza',$isabelle_de_souza);
        $this->addReference('julien_decoodt',$julien_decoodt);
        $this->addReference('martine_doucet',$martine_doucet);
        $this->addReference('geraldine_melina',$geraldine_melina);
        $this->addReference('serge_sachet',$serge_sachet);
        $this->addReference('philippe_melo',$philippe_melo);
        $this->addReference('herve_michel',$herve_michel);
        $this->addReference('michelle_milome',$michelle_milome);
        $this->addReference('matthieu_felix',$matthieu_felix);
        $this->addReference('laurent_geoffroy',$laurent_geoffroy);
        $this->addReference('laure_grapindor',$laure_grapindor);
        $this->addReference('anna_gausson',$anna_gausson);
        $this->addReference('victoire_gasparini',$victoire_gasparini);
        $this->addReference('jordan_ferdoss',$jordan_ferdoss);
        $this->addReference('henry_el_fezzazi',$henry_el_fezzazi);
        $this->addReference('mickael_dubois',$mickael_dubois);


    }

    /**
     * Crée et retourne un professeur avec des informations aléatoires.
     *
     * @return ParentEleve Nouveau parent
     */
    public function nouveauParentAleatoire($prenom, $nom, $refEleve)
    {
        $adresse = rand(0, 1);
        $parent = $this->nouveauParent(
            $prenom,
            $nom,
            $this->telephoneAleatoire(),
            $this->emailAleatoire($prenom, $nom),
            $refEleve
        );
        return $parent;
    }


    /**
     * Crée et retourne un nouveau lycéen avec les informations fournies.
     *
     * @param string $prenom
     * @param string $nom
     * @param string $portable
     * @param string $refeleve
     * @return Parent Nouveau parent
     */
    public function nouveauParent($prenom, $nom, $portable = null, $email = null, $refeleve = null)
    {

        $parent = new ParentEleve();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($parent);
        $mdp = $encoder->encodePassword('debug', $parent->getSalt());

        $parent->setPrenom($prenom)
            ->setNom($nom)
            ->setTelephone($portable)
            ->setMail($email)
            ->setMotDePasse($mdp)
            ->addEleve($this->getReference($refeleve))
            ->setUsername($prenom.$nom)
            ->setCheckMail(false);
        return $parent;
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
            'CEC\MembreBundle\DataFixtures\ORM\LoadEleves'
        );
    }
}
