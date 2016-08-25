<?php

namespace CEC\MembreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * DossierInscription
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DossierInscription
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "profession_pere", type = "text")
     *
     */
    private $professionPere ="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "profession_mere", type = "text")
     *
     */
    private $professionMere="";



    /**
     * @var string
     *
     * @ORM\COlumn(name="telephone_parent", type="string", length=15)
     * @Assert\Regex(
     *     pattern = "/^((0[1-7] ?)|\+33 ?[67] ?)([0-9]{2} ?){4}$/",
     *     message = "Le numéro de téléphone n'est pas valide."
     * )
     * @Assert\Length(
     *     max = 15,
     *     maxMessage = "Un numéro de téléphone ne peut excéder 15 caractères."
     * )
     */
    private $telephoneParent ="";

    /**
     * @var string
     *
     * @ORM\Column(name="mail_parent", type="string", length=150)
     * @Assert\Email(
     *     message = "L'adresse email n'est pas valide.",
     *     checkHost = true
     * )
     * @Assert\NotBlank(message = "L'adresse email ne peut être vide.")
     * @Assert\Length(
     *     max = 100,
     *     maxMessage = "L'adresse email ne peut excéder 255 caractères."
     * )
     */
    private $mailParent="";

    /**

     *
     * @var string
     *
     * @ORM\Column(name = "statut_parents", type = "string", length = 20)
     * @Assert\NotBlank(message = "Merci de spécifier le statut des parents.")
     * @Assert\Regex(
     *     pattern="/Mariés|Divorcés|Concubinage|Famille Monoparentale/",
     *     match=true,
     *     message="Merci de spécifier votre niveau de scolarité "
     *     )
     */
    private $statutParents = "Mariés";

    /**

     *
     * @var integer
     *
     * @ORM\Column(name = "nombre_personnes_a_charge", type = "integer")
     * @Assert\NotBlank(message = "Veuillez indiquer le nombre de personne à charge.")
     * @Assert\Range(
     *     min = 0,
     *     max = 9999,
     *     minMessage = "Le nombre de personnes à charge doit être supérieure à 0.",
     *     maxMessage = "Le nombre de personne à charge doit être inférieure à 9999."
     * )
     */
    private $nombrePersonnesACharge = 0;

    /**

     *
     * @var integer
     *
     * @ORM\Column(name = "nombre_enfants", type = "integer")
     * @Assert\NotBlank(message = "Veuillez indiquer le nombre d'enfants.")
     * @Assert\Range(
     *     min = 0,
     *     max = 9999,
     *     minMessage = "Le nombre d'enfants doit être supérieure à 0.",
     *     maxMessage = "Le nombre d'enfants doit être inférieure à 9999."
     * )
     */
    private $nombreEnfants = 0;

    /**
     * @var string
     * 
     * @ORM\Column(name = "enfants", type = "text")
     */
    private $enfants="";


    /**
     *
     * @var string
     *
     * @ORM\Column(name = "bourses", type = "string", length = 255)
     *
     */
    private $bourses="";

    /**

     *
     * @var boolean
     *
     * @ORM\Column(name = "raison_inscription_cec_participe_au_programme", type = "boolean")
     *
     */
    private $raisonInscriptionCecParticipeAuProgramme = false;

    /**

     *
     * @var string
     *
     * @ORM\Column(name = "nombre_annee_chez_cec", type = "string")
     * 
     */
    private $nombreAnneeChezCec ="";

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name = "raison_inscription_cec_encourage_par_proche", type = "boolean")
     *
     */
    private $raisonInscriptionCecEncourageParProche = false;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "proche_qui_a_encourage_pour_cec", type = "string", length = 255)
     *
     */
    private $procheQuiAEncouragePourCec="";

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name = "raison_inscription_cec_curiosite", type = "boolean")
     *
     */
    private $raisonInscriptionCecCuriosite = false;

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name = "raison_inscription_cec_programme_educatif", type = "boolean")
     *
     */
    private $raisonInscriptionCecProgrammeEducatif = false;

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name = "raison_inscription_cec_sorties_projets", type = "boolean")
     *
     */
    private $raisonInscriptionCecSortiesProjets = false;

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name = "raison_inscription_cec_lycee", type = "boolean")
     *
     */
    private $raisonInscriptionCecLycee = false;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "matieres_preferees", type = "string", length = 255)
     *
     */
    private $matieresPreferees="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "matieres_detestees", type = "string", length = 255)
     *
     */
    private $matieresDetestees="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "idee_orientation_post_bac", type = "string", length = 255)
     *
     */
    private $ideeOrientationPostBac="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "idee_metier", type = "string", length = 255)
     *
     */
    private $ideeMetier="";

    /**
     *
     * @var integer
     *
     * @ORM\Column(name = "aisance_oral", type = "integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 5,
     *     minMessage = "Ce n'est pas possible",
     *     maxMessage = "Ce n'est pas possible"
     * )
     *
     *
     */
    private $aisanceOral = 0;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name = "aisance_systeme_scolaire", type = "integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 5,
     *     minMessage = "Ce n'est pas possible",
     *     maxMessage = "Ce n'est pas possible"
     * )
     *
     *
     */
    private $aisanceSystemeScolaire = 0;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name = "capacite_obtention_etudes_souhaitees", type = "integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 5,
     *     minMessage = "Ce n'est pas possible",
     *     maxMessage = "Ce n'est pas possible"
     * )
     *
     */
    private $capaciteObtentionEtudesSouhaitees = 0;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name = "information_enseignement_superieur", type = "integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 5,
     *     minMessage = "Ce n'est pas possible",
     *     maxMessage = "Ce n'est pas possible"
     * )
     *
     *
     */
    private $informationEnseignementSuperieur = 0;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name = "attachement_actualites", type = "integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 5,
     *     minMessage = "Ce n'est pas possible",
     *     maxMessage = "Ce n'est pas possible"
     * )
     *
     *
     */
    private $attachementActualites = 0;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name = "interet_science", type = "integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 5,
     *     minMessage = "Ce n'est pas possible",
     *     maxMessage = "Ce n'est pas possible"
     * )
     *
     *
     */
    private $interetScience = 0;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "activites_extrascolaires", type = "string", length = 255)
     *
     */
    private $activitesExtrascolaires ="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_musee", type = "string", length = 255)
     *
     */
    private $pratiqueMusee="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_theatre", type = "string", length = 255)
     *
     */
    private $pratiqueTheatre="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_cinema", type = "string", length = 255)
     *
     */
    private $pratiqueCinema="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_journal_televise", type = "string", length = 255)
     *
     */
    private $pratiqueJournalTelevise="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_journaux", type = "string", length = 255)
     *
     */
    private $pratiqueJournaux="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_lecture", type = "string", length = 255)
     *
     */
    private $pratiqueLecture="";

    /**
     *
     * @var array
     *
     * @ORM\Column(name = "projets_cec_interets", type = "array", length = 255)
     */
    private $projetsCecInterets = [];

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "langue_vivante", type = "string", length = 255)
     *
     */
    private $langueVivante="";

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "correspondant_etranger", type = "text")
     *
     */
    private $correspondantEtranger="";

    /**
     *
     * @var integer
     *
     * @ORM\Column(name = "interet_europen", type = "integer")
     * @Assert\Range(
     *     min = 0,
     *     max = 10,
     *     minMessage = "Ce n'est pas possible",
     *     maxMessage = "Ce n'est pas possible"
     * )
     *
     */
    private $interetEuropen = 0;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "voyages_realises", type = "text")
     *
     */
    private $voyagesRealises="";

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getProfessionPere()
    {
        return $this->professionPere;
    }

    /**
     * @param string $professionPere
     */
    public function setProfessionPere($professionPere)
    {
        $this->professionPere = $professionPere;
    }

    /**
     * @return string
     */
    public function getProfessionMere()
    {
        return $this->professionMere;
    }

    /**
     * @param string $professionMere
     */
    public function setProfessionMere($professionMere)
    {
        $this->professionMere = $professionMere;
    }

    /**
     * @return string
     */
    public function getTelephoneParent()
    {
        return $this->telephoneParent;
    }

    /**
     * @param string $telephoneParent
     */
    public function setTelephoneParent($telephoneParent)
    {
        $this->telephoneParent = $telephoneParent;
    }

    /**
     * @return string
     */
    public function getMailParent()
    {
        return $this->mailParent;
    }

    /**
     * @param string $mailParent
     */
    public function setMailParent($mailParent)
    {
        $this->mailParent = $mailParent;
    }

    /**
     * @return string
     */
    public function getStatutParents()
    {
        return $this->statutParents;
    }

    /**
     * @param string $statutParents
     */
    public function setStatutParents($statutParents)
    {
        $this->statutParents = $statutParents;
    }

    /**
     * @return int
     */
    public function getNombrePersonnesACharge()
    {
        return $this->nombrePersonnesACharge;
    }

    /**
     * @param int $nombrePersonnesACharge
     */
    public function setNombrePersonnesACharge($nombrePersonnesACharge)
    {
        $this->nombrePersonnesACharge = $nombrePersonnesACharge;
    }

    /**
     * @return int
     */
    public function getNombreEnfants()
    {
        return $this->nombreEnfants;
    }

    /**
     * @param int $nombreEnfants
     */
    public function setNombreEnfants($nombreEnfants)
    {
        $this->nombreEnfants = $nombreEnfants;
    }

    /**
     * @return string
     */
    public function getEnfants()
    {
        return $this->enfants;
    }

    /**
     * @param string $enfants
     */
    public function setEnfants($enfants)
    {
        $this->enfants = $enfants;
    }

    /**
     * @return string
     */
    public function getBourses()
    {
        return $this->bourses;
    }

    /**
     * @param string $bourses
     */
    public function setBourses($bourses)
    {
        $this->bourses = $bourses;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecParticipeAuProgramme()
    {
        return $this->raisonInscriptionCecParticipeAuProgramme;
    }

    /**
     * @param boolean $raisonInscriptionCecParticipeAuProgramme
     */
    public function setRaisonInscriptionCecParticipeAuProgramme($raisonInscriptionCecParticipeAuProgramme)
    {
        $this->raisonInscriptionCecParticipeAuProgramme = $raisonInscriptionCecParticipeAuProgramme;
    }

    /**
     * @return string
     */
    public function getNombreAnneeChezCec()
    {
        return $this->nombreAnneeChezCec;
    }

    /**
     * @param string $nombreAnneeChezCec
     */
    public function setNombreAnneeChezCec($nombreAnneeChezCec)
    {
        $this->nombreAnneeChezCec = $nombreAnneeChezCec;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecEncourageParProche()
    {
        return $this->raisonInscriptionCecEncourageParProche;
    }

    /**
     * @param boolean $raisonInscriptionCecEncourageParProche
     */
    public function setRaisonInscriptionCecEncourageParProche($raisonInscriptionCecEncourageParProche)
    {
        $this->raisonInscriptionCecEncourageParProche = $raisonInscriptionCecEncourageParProche;
    }

    /**
     * @return string
     */
    public function getProcheQuiAEncouragePourCec()
    {
        return $this->procheQuiAEncouragePourCec;
    }

    /**
     * @param string $procheQuiAEncouragePourCec
     */
    public function setProcheQuiAEncouragePourCec($procheQuiAEncouragePourCec)
    {
        $this->procheQuiAEncouragePourCec = $procheQuiAEncouragePourCec;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecCuriosite()
    {
        return $this->raisonInscriptionCecCuriosite;
    }

    /**
     * @param boolean $raisonInscriptionCecCuriosite
     */
    public function setRaisonInscriptionCecCuriosite($raisonInscriptionCecCuriosite)
    {
        $this->raisonInscriptionCecCuriosite = $raisonInscriptionCecCuriosite;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecProgrammeEducatif()
    {
        return $this->raisonInscriptionCecProgrammeEducatif;
    }

    /**
     * @param boolean $raisonInscriptionCecProgrammeEducatif
     */
    public function setRaisonInscriptionCecProgrammeEducatif($raisonInscriptionCecProgrammeEducatif)
    {
        $this->raisonInscriptionCecProgrammeEducatif = $raisonInscriptionCecProgrammeEducatif;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecSortiesProjets()
    {
        return $this->raisonInscriptionCecSortiesProjets;
    }

    /**
     * @param boolean $raisonInscriptionCecSortiesProjets
     */
    public function setRaisonInscriptionCecSortiesProjets($raisonInscriptionCecSortiesProjets)
    {
        $this->raisonInscriptionCecSortiesProjets = $raisonInscriptionCecSortiesProjets;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecLycee()
    {
        return $this->raisonInscriptionCecLycee;
    }

    /**
     * @param boolean $raisonInscriptionCecLycee
     */
    public function setRaisonInscriptionCecLycee($raisonInscriptionCecLycee)
    {
        $this->raisonInscriptionCecLycee = $raisonInscriptionCecLycee;
    }

    /**
     * @return string
     */
    public function getMatieresPreferees()
    {
        return $this->matieresPreferees;
    }

    /**
     * @param string $matieresPreferees
     */
    public function setMatieresPreferees($matieresPreferees)
    {
        $this->matieresPreferees = $matieresPreferees;
    }

    /**
     * @return string
     */
    public function getMatieresDetestees()
    {
        return $this->matieresDetestees;
    }

    /**
     * @param string $matieresDetestees
     */
    public function setMatieresDetestees($matieresDetestees)
    {
        $this->matieresDetestees = $matieresDetestees;
    }

    /**
     * @return string
     */
    public function getIdeeOrientationPostBac()
    {
        return $this->ideeOrientationPostBac;
    }

    /**
     * @param string $ideeOrientationPostBac
     */
    public function setIdeeOrientationPostBac($ideeOrientationPostBac)
    {
        $this->ideeOrientationPostBac = $ideeOrientationPostBac;
    }

    /**
     * @return string
     */
    public function getIdeeMetier()
    {
        return $this->ideeMetier;
    }

    /**
     * @param string $ideeMetier
     */
    public function setIdeeMetier($ideeMetier)
    {
        $this->ideeMetier = $ideeMetier;
    }

    /**
     * @return int
     */
    public function getAisanceOral()
    {
        return $this->aisanceOral;
    }

    /**
     * @param int $aisanceOral
     */
    public function setAisanceOral($aisanceOral)
    {
        $this->aisanceOral = $aisanceOral;
    }

    /**
     * @return int
     */
    public function getAisanceSystemeScolaire()
    {
        return $this->aisanceSystemeScolaire;
    }

    /**
     * @param int $aisanceSystemeScolaire
     */
    public function setAisanceSystemeScolaire($aisanceSystemeScolaire)
    {
        $this->aisanceSystemeScolaire = $aisanceSystemeScolaire;
    }

    /**
     * @return int
     */
    public function getCapaciteObtentionEtudesSouhaitees()
    {
        return $this->capaciteObtentionEtudesSouhaitees;
    }

    /**
     * @param int $capaciteObtentionEtudesSouhaitees
     */
    public function setCapaciteObtentionEtudesSouhaitees($capaciteObtentionEtudesSouhaitees)
    {
        $this->capaciteObtentionEtudesSouhaitees = $capaciteObtentionEtudesSouhaitees;
    }

    /**
     * @return int
     */
    public function getInformationEnseignementSuperieur()
    {
        return $this->informationEnseignementSuperieur;
    }

    /**
     * @param int $informationEnseignementSuperieur
     */
    public function setInformationEnseignementSuperieur($informationEnseignementSuperieur)
    {
        $this->informationEnseignementSuperieur = $informationEnseignementSuperieur;
    }

    /**
     * @return int
     */
    public function getAttachementActualites()
    {
        return $this->attachementActualites;
    }

    /**
     * @param int $attachementActualites
     */
    public function setAttachementActualites($attachementActualites)
    {
        $this->attachementActualites = $attachementActualites;
    }

    /**
     * @return int
     */
    public function getInteretScience()
    {
        return $this->interetScience;
    }

    /**
     * @param int $interetScience
     */
    public function setInteretScience($interetScience)
    {
        $this->interetScience = $interetScience;
    }

    /**
     * @return string
     */
    public function getActivitesExtrascolaires()
    {
        return $this->activitesExtrascolaires;
    }

    /**
     * @param string $activitesExtrascolaires
     */
    public function setActivitesExtrascolaires($activitesExtrascolaires)
    {
        $this->activitesExtrascolaires = $activitesExtrascolaires;
    }

    /**
     * @return string
     */
    public function getPratiqueMusee()
    {
        return $this->pratiqueMusee;
    }

    /**
     * @param string $pratiqueMusee
     */
    public function setPratiqueMusee($pratiqueMusee)
    {
        $this->pratiqueMusee = $pratiqueMusee;
    }

    /**
     * @return string
     */
    public function getPratiqueTheatre()
    {
        return $this->pratiqueTheatre;
    }

    /**
     * @param string $pratiqueTheatre
     */
    public function setPratiqueTheatre($pratiqueTheatre)
    {
        $this->pratiqueTheatre = $pratiqueTheatre;
    }

    /**
     * @return string
     */
    public function getPratiqueCinema()
    {
        return $this->pratiqueCinema;
    }

    /**
     * @param string $pratiqueCinema
     */
    public function setPratiqueCinema($pratiqueCinema)
    {
        $this->pratiqueCinema = $pratiqueCinema;
    }

    /**
     * @return string
     */
    public function getPratiqueJournalTelevise()
    {
        return $this->pratiqueJournalTelevise;
    }

    /**
     * @param string $pratiqueJournalTelevise
     */
    public function setPratiqueJournalTelevise($pratiqueJournalTelevise)
    {
        $this->pratiqueJournalTelevise = $pratiqueJournalTelevise;
    }

    /**
     * @return string
     */
    public function getPratiqueJournaux()
    {
        return $this->pratiqueJournaux;
    }

    /**
     * @param string $pratiqueJournaux
     */
    public function setPratiqueJournaux($pratiqueJournaux)
    {
        $this->pratiqueJournaux = $pratiqueJournaux;
    }

    /**
     * @return string
     */
    public function getPratiqueLecture()
    {
        return $this->pratiqueLecture;
    }

    /**
     * @param string $pratiqueLecture
     */
    public function setPratiqueLecture($pratiqueLecture)
    {
        $this->pratiqueLecture = $pratiqueLecture;
    }

    /**
     * @return array
     */
    public function getProjetsCecInterets()
    {
        return $this->projetsCecInterets;
    }

    /**
     * @param array $projetsCecInterets
     */
    public function setProjetsCecInterets($projetsCecInterets)
    {
        $this->projetsCecInterets = $projetsCecInterets;
    }

    /**
     * @return string
     */
    public function getLangueVivante()
    {
        return $this->langueVivante;
    }

    /**
     * @param string $langueVivante
     */
    public function setLangueVivante($langueVivante)
    {
        $this->langueVivante = $langueVivante;
    }

    /**
     * @return string
     */
    public function getCorrespondantEtranger()
    {
        return $this->correspondantEtranger;
    }

    /**
     * @param string $correspondantEtranger
     */
    public function setCorrespondantEtranger($correspondantEtranger)
    {
        $this->correspondantEtranger = $correspondantEtranger;
    }

    /**
     * @return int
     */
    public function getInteretEuropen()
    {
        return $this->interetEuropen;
    }

    /**
     * @param int $interetEuropen
     */
    public function setInteretEuropen($interetEuropen)
    {
        $this->interetEuropen = $interetEuropen;
    }

    /**
     * @return string
     */
    public function getVoyagesRealises()
    {
        return $this->voyagesRealises;
    }

    /**
     * @param string $voyagesRealises
     */
    public function setVoyagesRealises($voyagesRealises)
    {
        $this->voyagesRealises = $voyagesRealises;
    }


}
