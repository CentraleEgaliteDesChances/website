<?php

namespace CEC\MainBundle\Utility;

/**
 * Représente une année scolaire.
 */
class AnneeScolaire
{
    /**
     * Année inférieure des deux années.
     * L'année scolaire est représentée par son année inférieure, qui doit donc
     * être non vide, et composé d'un entier naturel de 4 caractères, compris
     * entre 1000 et 2999.
     *
     * @var integer
     */
    private $anneeInferieure;
    
    
    
    /** Constructeurs
     */
     
    /**
     * Constructeur par défaut.
     * L'année scolaire par défaut est l'année scolaire 2011-2012, année de création du site.
     *
     * @return AnneeScolaire : année scolaire 2011-2012.
     */
    public function __construct() {
        $this->setAnneeInferieure(2011);
        return $this;
    }
    
    /**
     * Constructeur avec une date.
     * L'année scolaire est crée à partir de la date passée en argument.
     * Si la date passée en argument ne se situe dans aucune année scolaire
     * (entre Juillet et Août), une exception est levée.
     * Si aucune date n'est passée en argument, la date actuelle est utilisée.
     *
     * @param DateTime() $date : date à utiliser
     * @return AnneeScolaire : la nouvelle année scolaire.
     */
    public static function withDate(\DateTime $date = null) {
        if (!$date) $date = new \DateTime();
        $anneeScolaire = new self();
        $anneeScolaire->setAnneeInferieure($date->format('Y') + AnneeScolaire::termePourAnneeInferieure($date));
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
            throw new \Exception("Impossible de parser l'année scolaire !");
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
     * La date de la rentrée est définie comme le 1er septembre de l'année inférieure.
     *
     * @return DateTime : date de la rentrée.
     */
    public function getDateRentree() {
        return new \DateTime($this->getAnneeInferieure() . "-09-01");
    }
    
    /**
     * Retourne la date de la sortie des classes.
     * La date de la sortie des classes est définie comme le 1er juillet de l'année supérieure.
     *
     * @return DateTime : date de la sortie des classes.
     */
    public function getDateSortie() {
        return new \DateTime($this->getAnneeSuperieure() . "-07-01");
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
        return ($date >= $this->getDateRentree()) &&
                    ($date < $this->getDateSortie());
    }
    
    /**
     * Retourne le terme additionnel permettant d'obtenir l'année inférieure
     * de l'année scolaire correspondant à la date passée en argument.
     * Si la date est comprise entre Janvier et Juin, la méthode renvoit -1 ; si
     * elle est comprise entre Septembre et Décembre, la méthode renvoit 0.
     * Sinon, une exception est levée.
     *
     * @param DateTime $date : date.
     * @return integer : terme additionel.
     */
    public function termePourAnneeInferieure(\DateTime $date) {
        $mois = $date->format('m');
        if ($mois > 6 && $mois < 9) {
            throw new \Exception("La date ne se situe dans aucune année scolaire !");
        }
        return ($mois < 7) ? -1 : 0;
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
        return ($annee >= 1000) && ($annee < 3000);
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
