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
    private $professionPere;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "profession_mere", type = "text")
     *
     */
    private $professionMere;



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
    private $telephoneParent;

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
    private $mailParent;

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
    private $statutParents;

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
    private $nombrePersonnesACharge;

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
    private $nombreEnfants;

    /**
     * Dit si un des enfants est titulaire d'une bourse
     *
     *@ORM\Column(name="boursier", type="boolean")
     */
    private $boursier = false;

    /**

     *
     * @var string
     *
     * @ORM\Column(name = "bourses", type = "string", length = 255)
     *
     */
    private $bourses;

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
     * @var integer
     *
     * @ORM\Column(name = "nombre_annee_chez_cec", type = "integer")
     * @Assert\Range(
     *     min = 2,
     *     max = 3,
     *     minMessage = "Ce n'est pas possible",
     *     maxMessage = "Ce n'est pas possible"
     * )
     * 
     * 
     */
    private $nombreAnneeChezCec;

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
    private $procheQuiAEncouragePourCec;

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
    private $matieresPreferees;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "matieres_detestees", type = "string", length = 255)
     *
     */
    private $matieresDetestees;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "idee_orientation_post_bac", type = "string", length = 255)
     *
     */
    private $ideeOrientationPostBac;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "idee_metier", type = "string", length = 255)
     *
     */
    private $ideeMetier;

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
    private $aisanceOral;

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
    private $aisanceSystemeScolaire;

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
    private $capaciteObtentionEtudesSouhaitees;

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
    private $informationEnseignementSuperieur;

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
    private $attachementActualites;

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
    private $interetScience;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "activites_extrascolaires", type = "string", length = 255)
     *
     */
    private $activitesExtrascolaires;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_musee", type = "string", length = 255)
     *
     */
    private $pratiqueMusee;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_theatre", type = "string", length = 255)
     *
     */
    private $pratiqueTheatre;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_cinema", type = "string", length = 255)
     *
     */
    private $pratiqueCinema;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_journal_televise", type = "string", length = 255)
     *
     */
    private $pratiqueJournalTelevise;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_journaux", type = "string", length = 255)
     *
     */
    private $pratiqueJournaux;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "pratique_lecture", type = "string", length = 255)
     *
     */
    private $pratiqueLecture;

    /**
     *
     * @var array
     *
     * @ORM\Column(name = "projets_cec_interets", type = "array", length = 255)
     *
     */
    private $projetsCecInterets;

    /**
     *
     * @var array
     *
     * @ORM\Column(name = "langue_vivante", type = "array", length = 255)
     *
     */
    private $langueVivante;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "correspondant_etranger", type = "text")
     *
     */
    private $correspondantEtranger;

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
    private $interetEuropen;

    /**
     *
     * @var string
     *
     * @ORM\Column(name = "voyages_realises", type = "text")
     *
     */
    private $voyagesRealises;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getProfessionPere()
    {
        return $this->professionPere;
    }

    /**
     * @return string
     */
    public function getProfessionMere()
    {
        return $this->professionMere;
    }
    
    /**
     * @return string
     */
    public function getTelephoneParent()
    {
        return $this->telephoneParent;
    }

    /**
     * @return string
     */
    public function getMailParent()
    {
        return $this->mailParent;
    }

    /**
     * @return string
     */
    public function getStatutParents()
    {
        return $this->statutParents;
    }

    /**
     * @return int
     */
    public function getNombrePersonnesACharge()
    {
        return $this->nombrePersonnesACharge;
    }

    /**
     * @return int
     */
    public function getNombreEnfants()
    {
        return $this->nombreEnfants;
    }

    /**
     * @return mixed
     */
    public function getBoursier()
    {
        return $this->boursier;
    }

    /**
     * @return string
     */
    public function getBourses()
    {
        return $this->bourses;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecParticipeAuProgramme()
    {
        return $this->raisonInscriptionCecParticipeAuProgramme;
    }

    /**
     * @return int
     */
    public function getNombreAnneeChezCec()
    {
        return $this->nombreAnneeChezCec;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecEncourageParProche()
    {
        return $this->raisonInscriptionCecEncourageParProche;
    }

    /**
     * @return string
     */
    public function getProcheQuiAEncouragePourCec()
    {
        return $this->procheQuiAEncouragePourCec;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecCuriosite()
    {
        return $this->raisonInscriptionCecCuriosite;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecProgrammeEducatif()
    {
        return $this->raisonInscriptionCecProgrammeEducatif;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecSortiesProjets()
    {
        return $this->raisonInscriptionCecSortiesProjets;
    }

    /**
     * @return boolean
     */
    public function isRaisonInscriptionCecLycee()
    {
        return $this->raisonInscriptionCecLycee;
    }

    /**
     * @return string
     */
    public function getMatieresPreferees()
    {
        return $this->matieresPreferees;
    }

    /**
     * @return string
     */
    public function getMatieresDetestees()
    {
        return $this->matieresDetestees;
    }

    /**
     * @return string
     */
    public function getIdeeOrientationPostBac()
    {
        return $this->ideeOrientationPostBac;
    }

    /**
     * @return string
     */
    public function getIdeeMetier()
    {
        return $this->ideeMetier;
    }

    /**
     * @return int
     */
    public function getAisanceOral()
    {
        return $this->aisanceOral;
    }

    /**
     * @return int
     */
    public function getAisanceSystemeScolaire()
    {
        return $this->aisanceSystemeScolaire;
    }

    /**
     * @return int
     */
    public function getCapaciteObtentionEtudesSouhaitees()
    {
        return $this->capaciteObtentionEtudesSouhaitees;
    }

    /**
     * @return int
     */
    public function getInformationEnseignementSuperieur()
    {
        return $this->informationEnseignementSuperieur;
    }

    /**
     * @return int
     */
    public function getAttachementActualites()
    {
        return $this->attachementActualites;
    }

    /**
     * @return int
     */
    public function getInteretScience()
    {
        return $this->interetScience;
    }

    /**
     * @return string
     */
    public function getActivitesExtrascolaires()
    {
        return $this->activitesExtrascolaires;
    }

    /**
     * @return string
     */
    public function getPratiqueMusee()
    {
        return $this->pratiqueMusee;
    }

    /**
     * @return string
     */
    public function getPratiqueTheatre()
    {
        return $this->pratiqueTheatre;
    }

    /**
     * @return string
     */
    public function getPratiqueCinema()
    {
        return $this->pratiqueCinema;
    }

    /**
     * @return string
     */
    public function getPratiqueJournalTelevise()
    {
        return $this->pratiqueJournalTelevise;
    }

    /**
     * @return string
     */
    public function getPratiqueJournaux()
    {
        return $this->pratiqueJournaux;
    }

    /**
     * @return string
     */
    public function getPratiqueLecture()
    {
        return $this->pratiqueLecture;
    }

    /**
     * @return array
     */
    public function getProjetsCecInterets()
    {
        return $this->projetsCecInterets;
    }

    /**
     * @return array
     */
    public function getLangueVivante()
    {
        return $this->langueVivante;
    }

    /**
     * @return string
     */
    public function getCorrespondantEtranger()
    {
        return $this->correspondantEtranger;
    }

    /**
     * @return int
     */
    public function getInteretEuropen()
    {
        return $this->interetEuropen;
    }

    /**
     * @return string
     */
    public function getVoyagesRealises()
    {
        return $this->voyagesRealises;
    }

    
}
