<?php

namespace CEC\ActiviteBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Représente un feedback sur une activité effectuée en séance de tutorat.
 *
 * Le compte-rendu est créé dès que le VP Lycée (ou qu'un tuteur) choisit une activité pour sa séance.
 * Le compte-rendu alors vierge connecte l'activité avec la séance de tutorat (classe Seance).
 * Le feedback est ensuite rédigé par un VP Lycée ou un tuteur à la suite de la séance.
 * Il est ensuite consulté par les membres des secteurs Activités qui cherchent à améliorer ou
 * corriger les défauts des activités en soumettant une nouvelle version.
 *
 * Il faut rendre la rédaction d'un compte-rendu très facile : c'est pourquoi 3 critères simples 
 * ont été précisément définis, plus une appréciation de la durée nécessaire pour réaliser 
 * l'activité ainsi qu'un champ de commentaires libres.
 *
 * Les notes sont définies sur 5, 5 étant la note maximale (Très bon avis).
 * La méthode getNoteGlobale() permet d'obtenir une moyenne des notes associées au compte-rendu.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\ActiviteBundle\Entity\CompteRenduRepository")
 */
class CompteRendu
{
    /**
     * Durée annoncée trop courte.
     * La durée estimée est sous-estimée.
     * @var integer
     */
    const CompteRenduDureeAnnonceeTropCourte = -1;
    
    /**
     * Durée annoncée adaptée.
     * La durée estimée est proche de la réalité
     * @var integer
     */
    const CompteRenduDureeAdaptee = 0;
    
    /**
     * Durée annoncée trop longue.
     * La durée estimée est sur-estimée.
     * @var integer
     */
    const CompteRenduDureeAnnonceeTropLongue = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * Note de contenu.
     * Le contenu du sujet est-il interessant et possède-t-il un contenu pédagogique pertinent ?
     * La note est un entier, compris entre 1 (Mauvais) et 5 (Très bon avis).
     *
     * @var integer
     *
     * @ORM\Column(name = "noteContenu", type = "integer")
     * @Assert\NotBlank(message = "Vous devez attribuer une note de contenu.")
     * @Assert\Range(
     *     min = 1,
     *     max = 5,
     *     minMessage = "La note de contenu doit être un entier compris entre 1 (Mauvais avis) et 5 (Très bon avis).",
     *     maxMessage = "La note de contenu doit être un entier compris entre 1 (Mauvais avis) et 5 (Très bon avis).",
     *     invalidMessage = "La note de contenu doit être un entier compris entre 1 (Mauvais avis) et 5 (Très bon avis)."
     * )
     */
    private $noteContenu;

    /**
     * Note d'interactivité.
     * Le sujet est-il suffisamment interactif et ludique ? A-t-il été globalement
     * bien suivi par les lycéens ?
     * La note est un entier, compris entre 1 (Mauvais) et 5 (Très bon avis).
     *
     * @var integer
     *
     * @ORM\Column(name = "noteInteractivite", type = "integer")
     * @Assert\NotBlank(message = "Vous devez attribuer une note d'interactivité.")
     * @Assert\Range(
     *     min = 1,
     *     max = 5,
     *     minMessage = "La note d'interactivité doit être un entier compris entre 1 (Mauvais avis) et 5 (Très bon avis).",
     *     maxMessage = "La note d'interactivité doit être un entier compris entre 1 (Mauvais avis) et 5 (Très bon avis).",
     *     invalidMessage = "La note d'interactivité doit être un entier compris entre 1 (Mauvais avis) et 5 (Très bon avis)."
     * )
     */
    private $noteInteractivite;

    /**
     * Note d'atteinte d'objectifs.
     * Les objectifs pédagogiques annocés de l'activité ont-ils été atteints à la fin de la séance ?
     * La note est un entier, compris entre 1 (Mauvais) et 5 (Très bon avis).
     *
     * @var integer
     *
     * @ORM\Column(name = "noteAtteinteObjectifs", type = "integer")
     * @Assert\NotBlank(message = "Vous devez attribuer une note d'atteinte des objectifs.")
     * @Assert\Range(
     *     min = 1,
     *     max = 5,
     *     minMessage = "La note d'atteinte d'objectifs doit être un entier compris entre 1 (Mauvais avis) et 5 (Très bon avis).",
     *     maxMessage = "La note d'atteinte d'objectifs doit être un entier compris entre 1 (Mauvais avis) et 5 (Très bon avis).",
     *     invalidMessage = "La note d'atteinte d'objectifs doit être un entier compris entre 1 (Mauvais avis) et 5 (Très bon avis)."
     * )
     */
    private $noteAtteinteObjectifs;

    /**
     * La durée de l'activité est-elle adaptée ?
     * Spécifie si la durée réelle de l'activité est en accord avec celle qui est
     * annoncée sur la description de l'annonce ?
     * Cette valeur doit être non-nulle et les valeurs acceptées sont définies par les
     * constantes de classes suivantes CompteRenduDuree(AnnonceeTropCourte|AnnonceeTropLongue\Adaptee).
     * 
     * @var integer
     *
     * @ORM\Column(name = "dureeAdaptee", type = "integer")
     * @Assert\NotBlank(message = "Vous devez spécifier votre appréciation sur la durée de l'activité.")
     * @Assert\Choice(
     *     choices = { -1, 0, 1 },
     *     message = "Votre appréciation sur la durée de l'activité n'est pas valide."
     * )
     */
    private $dureeAdaptee;

    /**
     * Espace libre pour des commentaires divers.
     * Permet de laisser un espace de libre expression afin de transmettre des retours
     * constructifs au secteur Activité et aux prochains tuteurs, dans le but de les aider
     * lors de la procédure de choix d'activités.
     * Cette chaîne de caractères peut-être laissé vide, mais ne doit pas dépasser 1000 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "commentaires", type = "text", nullable = true)
     * @Assert\MaxLength(
     *     limit = 1000,
     *     message = "Vos commentaires ne peuvent excéder 1000 caractères."
     * )
     */
    private $commentaires;

    /**
     * Date de création.
     *
     * @var \DateTime
     *
     * @ORM\Column(name = "dateCreation", type = "datetime")
     * @Assert\NotBlank()
     */
    private $dateCreation;

    /**
     * Date de dernière modification.
     *
     * @var \DateTime
     *
     * @ORM\Column(name = "dateModification", type = "datetime")
     * @Assert\NotBlank()
     */
    private $dateModification;
    
    /**
     * Activité associée au compte-rendu.
     * Permet d'associer l'activité (classe Activite) pour laquelle on formule des critiques.
     * Un compte-rendu doit obligatoirement être associé à une activité.
     *
     * @var Activite
     *
     * @ORM\ManyToOne(targetEntity = "Activite", inversedBy = "compteRendus")
     * @Assert\NotBlank(message = "Le compte-rendu doit être associé à une activité.")
     */
    private $activite;
    
    /**
     * Séance associée à ce compte-rendu.
     * Il s'agit de la séance pour laquelle ce compte-rendu a été rédigée. Par ailleurs,
     * lorsqu'un tuteur sélectionne une activité pour une séance, un compte-rendu vierge est
     * créé ; il sera remplie par la suite, à l'issue de la séance de tutorat.
     * 
     * @var CEC\TutoratBundle\Entity\Seance
     *
     * @ORM\ManyToOne(targetEntity = "CEC\TutoratBundle\Entity\Seance", inversedBy = "compteRendus")
     */
    private $seance;
    
    /**
     * Membre auteur du compte-rendu.
     * Il est enregistré lors de la rédaction du compte-rendu (après la séance) et permet de garder
     * une trace de l'activité du membre dans l'activité de tutorat.
     * Un compte-rendu doit obligatoirement être associé à un auteur (classe Membre) si il est rédigé.
     * Lors de la sélection de l'activité, le compte-rendu est créé mais aucun auteur n'est spécifié.
     *
     * @var CEC\MembreBundle\Entity\Membre
     *
     * @ORM\ManyToOne(targetEntity = "CEC\MembreBundle\Entity\Membre", inversedBy = "documents")
     * @Assert\NotBlank(message = "Le compte-rendu doit être associé à un auteur.")
     */
    private $auteur;
    
    
    /**
     * Retourne la moyenne des notes décernées dans le compte-rendu, sur 5.
     * Retourne false si une des notes n'est pas définie.
     *
     * @return integer Note globale attribuée à l'activité, sur 5.
     */
    public function getNoteGlobale()
    {
        if ($this->getNoteContenu() and 
            $this->getNoteInteractivite() and 
            $this->getNoteAtteinteObjectifs())
        {
            return ($this->getNoteContenu() + $this->getNoteInteractivite() + $this->getNoteAtteinteObjectifs()) / 3;
        } else {
            return false;
        }
    }
    
    /**
     * Description d'un compte-rendu.
     * Retourne la description de l'activité, de l'auteur auteur et sa note globale.
     *
     * @return string Description d'un compte-rendu.
     */
    public function __toString()
    {
        return 'Compte-rendu pour l\‘activité "' . $this->getActivite() . '" (' . $this->getAuteur() . ') : ' . $this->getNoteGlobale() ;
    }

    
    
    //
    // Doctrine-generated accessors
    //

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
     * Set noteContenu
     *
     * @param integer $noteContenu
     * @return CompteRendu
     */
    public function setNoteContenu($noteContenu)
    {
        $this->noteContenu = $noteContenu;
    
        return $this;
    }

    /**
     * Get noteContenu
     *
     * @return integer 
     */
    public function getNoteContenu()
    {
        return $this->noteContenu;
    }

    /**
     * Set noteInteractivite
     *
     * @param integer $noteInteractivite
     * @return CompteRendu
     */
    public function setNoteInteractivite($noteInteractivite)
    {
        $this->noteInteractivite = $noteInteractivite;
    
        return $this;
    }

    /**
     * Get noteInteractivite
     *
     * @return integer 
     */
    public function getNoteInteractivite()
    {
        return $this->noteInteractivite;
    }

    /**
     * Set noteAtteinteObjectifs
     *
     * @param integer $noteAtteinteObjectifs
     * @return CompteRendu
     */
    public function setNoteAtteinteObjectifs($noteAtteinteObjectifs)
    {
        $this->noteAtteinteObjectifs = $noteAtteinteObjectifs;
    
        return $this;
    }

    /**
     * Get noteAtteinteObjectifs
     *
     * @return integer 
     */
    public function getNoteAtteinteObjectifs()
    {
        return $this->noteAtteinteObjectifs;
    }

    /**
     * Set dureeAdaptee
     *
     * @param integer $dureeAdaptee
     * @return CompteRendu
     */
    public function setDureeAdaptee($dureeAdaptee)
    {
        $this->dureeAdaptee = $dureeAdaptee;
    
        return $this;
    }

    /**
     * Get dureeAdaptee
     *
     * @return integer 
     */
    public function getDureeAdaptee()
    {
        return $this->dureeAdaptee;
    }

    /**
     * Set commentaires
     *
     * @param string $commentaires
     * @return CompteRendu
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    
        return $this;
    }

    /**
     * Get commentaires
     *
     * @return string 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return CompteRendu
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return CompteRendu
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    
        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set activite
     *
     * @param \CEC\ActiviteBundle\Entity\Activite $activite
     * @return CompteRendu
     */
    public function setActivite(\CEC\ActiviteBundle\Entity\Activite $activite = null)
    {
        $this->activite = $activite;
    
        return $this;
    }

    /**
     * Get activite
     *
     * @return \CEC\ActiviteBundle\Entity\Activite 
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set auteur
     *
     * @param \CEC\MembreBundle\Entity\Membre $auteur
     * @return CompteRendu
     */
    public function setAuteur(\CEC\MembreBundle\Entity\Membre $auteur = null)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return \CEC\MembreBundle\Entity\Membre 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set seance
     *
     * @param \CEC\TutoratBundle\Entity\Seance $seance
     * @return CompteRendu
     */
    public function setSeance(\CEC\TutoratBundle\Entity\Seance $seance = null)
    {
        $this->seance = $seance;
    
        return $this;
    }

    /**
     * Get seance
     *
     * @return \CEC\TutoratBundle\Entity\Seance 
     */
    public function getSeance()
    {
        return $this->seance;
    }
}