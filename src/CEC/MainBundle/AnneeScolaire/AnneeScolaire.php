<?php

namespace CEC\MainBundle\AnneeScolaire;

use CEC\MainBundle\AnneeScolaire\Exception\MauvaisFormatAnneeException;

/**
 * Représente une année scolaire.
 */
class AnneeScolaire
{
    /**
     * Numéro du premier mois appartenant à une année scolaire.
     */
    const PREMIER_MOIS = 9;
    
    /**
     * Année inférieure par défaut.
     */
    const ANNEE_INFERIEURE_DEFAUT = 2011;

    /**
     * Année inférieure des deux années.
     * L'année scolaire est représentée par son année inférieure, qui doit donc
     * être non vide, et composée d'un entier naturel de 4 caractères compris
     * entre 1000 et 2999.
     *
     * @var integer
     */
    private $anneeInferieure;
    
    
    
    /** Constructeurs
     */
     
    /**
     * Constructeur par défaut.
     * L'argument, optionnel, correspond à l'année inférieure de l'année scolaire.
     * Si aucun argument n'est passé, l'année inférieure est l'année par défaut.
     *
     * @param interger or string $anneeInferieure : année inférieure, optionnelle
     * @return AnneeScolaire : année scolaire construit à partir de l'année inférieure.
     */
    public function __construct($anneeInferieure = null) {
        $this->anneeInferieure = self::ANNEE_INFERIEURE_DEFAUT;
        if ($anneeInferieure) $this->setAnneeInferieure(intval($anneeInferieure));
        return $this;
    }
    
    /**
     * Constructeur avec une date.
     * L'année scolaire est crée à partir de la date passée en argument.
     * Si aucune date n'est passée en argument, la date actuelle est utilisée.
     *
     * @param DateTime() $date : date à utiliser
     * @return AnneeScolaire : la nouvelle année scolaire.
     */
    public static function withDate(\DateTime $date = null) {
        if (!$date) $date = new \DateTime();
        $anneeScolaire = new self();
        $anneeScolaire->setAnneeInferieure(AnneeScolaire::anneeInferieurePourDate($date));
        return $anneeScolaire;
    }
    
    /**
     * Constructeur avec les deux années.
     * La chaîne de caractère est parsée et validée : il doit s'agir de
     * deux année consécutives de 4 caractères, compris entre 1000 et 2999,
     * séparées par un trait d'union ("-") ou un slash ("/").
     *
     * @param string $deuxAnnees : les deux années
     * @return AnneeScolaire : la nouvelle année scolaire.
     */
    public static function withAnnees($deuxAnnees) {
        $separateurs = array("-", "/");
        try {
            $anneeInferieure = substr($deuxAnnees, 0, 4);
            $anneeSuperieure = substr($deuxAnnees, 5, 4);
            $separateur = substr($deuxAnnees, 4, 1);
            if (!AnneeScolaire::validerAnnee($anneeInferieure) ||
                !AnneeScolaire::validerAnnee($anneeSuperieure) ||
                !in_array($separateur, $separateurs) ||
                intval($anneeSuperieure) <> intval($anneeInferieure + 1)) {
                throw new \Exception;
            }
        } catch (\Exception $exception) {
            throw new MauvaisFormatAnneeException("Le format des deux années n'est pas celui attendu !");
        }
        
        $anneeScolaire = new self();
        $anneeScolaire->setAnneeInferieure(intval($anneeInferieure));
        return $anneeScolaire;
    }
    
    
    /** Setters et getters
     */
    
    /**
     * Retourne l'année inférieure.
     *
     * @return integer : année inférieure.
     */
    public function getAnneeInferieure() {
        return $this->anneeInferieure;
    }
    
    /**
     * Retourne l'année supérieure.
     *
     * @return integer : année supérieure.
     */
    public function getAnneeSuperieure() {
        return $this->anneeInferieure + 1;
    }
    
    /**
     * Modifie l'année inférieure.
     *
     * @param integer $anneeInferieure : année inférieure
     */
    public function setAnneeInferieure($anneeInferieure) {
        if (AnneeScolaire::validerAnnee($anneeInferieure)) $this->anneeInferieure = $anneeInferieure;
        return $this;
    }
    
    /**
     * Modifie l'année supérieure.
     *
     * @param integer $anneeInferieure : année supérieure
     */
    public function setAnneeSuperieure($anneeSuperieure) {
        if (AnneeScolaire::validerAnnee($anneeSuperieure)) $this->anneeInferieure = $anneeSuperieure - 1;
        return $this;
    }
    
    /**
     * Retourne la date de la rentrée.
     * La date de la rentrée est définie comme le 1er du premier mois l'année scolaire.
     *
     * @return DateTime : date de la rentrée.
     */
    public function getDateRentree() {
        return new \DateTime($this->getAnneeInferieure() . "-" . self::PREMIER_MOIS . "-01");
    }
    
    /**
     * Retourne la date du dernier jour de l'année scolaire.
     * La date du dernier jour de l'année scolaire est définie comme le dernier jour du dernier
     * mois de l'année scolaire.
     *
     * @return DateTime : date de la rentrée.
     */
    public function getDateDernierJour() {
        $dateDernierJour = new \DateTime($this->getAnneeSuperieure() . "-" . self::PREMIER_MOIS . "-01");
        $dateDernierJour->add(\DateInterval::createFromDateString('-1 day'));
        return $dateDernierJour;
    }
    
    
    /** Relations date / année scolaire
     */
     
    /**
     * Vérifie si une date appartient à l'année scolaire.
     * Une date appartient à une année scolaire si elle est comprise entre
     * la date de rentrée des classes et la date de sorties des classes.
     *
     * @param DateTime: date
     * @return boolean : la date est-elle dans l'année scolaire ?
     */
    public function contientDate(\DateTime $date) {
        return ( $date >= $this->getDateRentree()     &&
                 $date < $this->getDateDernierJour()  );
    }
    
    /**
     * Retourne l'année inférieure correspondant à la date passée en argument.
     * Celle-ci est calculée en fonction du dernier mois de l'année scolaire : si
     * celui-ci a été dépassée, alors on renvoit l'année de l'argument. Sinon, on
     * renvoit l'année précédent l'année de l'argument.
     *
     * @param DateTime $date : date.
     * @return integer : année inférieure correspondant à la date passée en argument
     */
    public static function anneeInferieurePourDate(\DateTime $date) {
        $mois = $date->format('m');
        $annee = $date->format('Y');
        if ($mois < self::PREMIER_MOIS) $annee--;
        return $annee;
    }
        
    
    /** Affiche les descriptions
     */
    
    /**
     * Retourne la description de l'année scolaire.
     * La description est composée du texte "Année scolaire " suivi des deux années,
     * inférieure et supérieure, séparées par un trait d'union ("-").
     *
     * @return string : la description de l'année scolaire.
     */
    public function __toString() {
        return "Année scolaire " . $this->afficherAnnees();
    }
    
    /**
     * Retourne les deux années (supérieure et inférieures).
     * Les deux années sont séparées par un trait d'union ("-").
     *
     * @return string : les deux années.
     */
    public function afficherAnnees() {
        return $this->getAnneeInferieure() . "-" . $this->getAnneeSuperieure();
    }
    
    
    /** Validation des données
     */
    
    /**
     * Valide une année.
     * Une année est valide s'il s'agit d'un entier naturel de 4 caractères,
     * compris entre 1000 et 2999. Dans ce cas, la méthode renvoit "true".
     *
     * @param integer or string $annee : année à valider
     * @return boolean : résultat de la validation.
     */
    protected static function validerAnnee($annee) {
        $annee = intval($annee);
        $validation = ($annee >= 1000) && ($annee < 3000);
        if (!$validation) {
            throw new MauvaisFormatAnneeException("Le format de l'année ('" . $annee . "') ne correspond pas à celui attendu !");
            return false;
        }
        
        return true;
    }
    
    
    /** Ordre et égalité.
     */
    
    /**
     * Compare deux années scolaires.
     * La relation d'ordre est la même que celle définie sur les
     * entiers naturels, appliquée à l'année inférieure.
     *
     * @param AnneeScolaire $annee : première année à comparer.
     * @param AnneeScolaire $autreAnnee : deuxième année.
     * @return boolean : résultat de la comparaison.
     */
    public static function comparer(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? -1 : 1;
    }
}
