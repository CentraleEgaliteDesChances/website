<?php

namespace CEC\MembreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Eleve
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CEC\MembreBundle\Entity\EleveRepository")
 * @UniqueEntity(fields="mail", message="Email already taken")
 */
class Eleve implements UserInterface, \Serializable
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
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=100)
	 * @Assert\NotBlank(message = "Le prénom ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "Le prénom ne peut excéder 100 caractères."
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
	 * @Assert\NotBlank(message = "Le nom de famille ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "Le nom de famille ne peut excéder 100 caractères."
     * )
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=150)
	 * @Assert\Email(
     *     message = "L'adresse email n'est pas valide.",
     *     checkHost = true
     * )
     * @Assert\NotBlank(message = "L'adresse email ne peut être vide.")
     * @Assert\MaxLength(
     *     limit = 100,
     *     message = "L'adresse email ne peut excéder 255 caractères."
     * )
     */
    private $mail;
	
	/**
     * Numéro de téléphone du membre.
     * Ce champ n'est pas requis mais permet de pouvoir contacter par téléphone
     * (fixe ou portable) un tuteur en cas de besoin. La syntaxe du numéro est vérifiée.
     *
     * @var string
     *
     * @ORM\Column(name = "telephone", type = "string", length = 15, nullable = true)
     * @Assert\Regex(
     *     pattern = "/^((0[1-7] ?)|\+33 ?[67] ?)([0-9]{2} ?){4}$/",
     *     message = "Le numéro de téléphone n'est pas valide."
     * )
     * @Assert\MaxLength(
     *     limit = 15,
     *     message = "Un numéro de téléphone ne peut excéder 15 caractères."
     * )
     */
    private $telephone;
	 
	 /**
	 * @var boolean
	 *
	 * @ORM\Column(name="telephonePublic", type="boolean")
	 */
	 private $telephonePublic = false;
	 
	 /**
     * @var string
     *
     * @ORM\Column(name="lycee", type="string", length=20)
     */
    private $lycee;

    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="string", length=5)
     */
    private $classe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datenaiss", type="datetime")
     */
    private $datenaiss;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;
	
	    /**
     * Mot de passe hashé du membre.
     * Ce champ contient le mot de passe en clair lors de sa modification par
     * formulaire, avant d'être hashé et remplaçé dans ce champ. Dans la BDD, seul
     * le mot de passe hashé est donc enregistré — et comparé lors de l'authentification.
     *
     * Le mot de passe, requis, est une chaîne de caractère de longueur comprise entre 5 et 100 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "motDePasse", type = "string", length = 100)
     * @Assert\NotBlank(message = "Merci de spécifier un mot de passe.")
     * @Assert\Type(
     *     type = "string",
     *     message = "Le mot de passe doit être une chaîne de caractères."
     * )
     * @Assert\Length(
     *     min = "5",
     *     max = "100",
     *     minMessage = "Le mot de passe doit contenir au moins 5 caractères.",
     *     maxMessage = "Le mot de passe ne peut excéder 100 caractères."
     * )
     */
    private $motDePasse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
	 * @Gedmo\Timestampable(on = "create")
     * @Assert\DateTime()
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModification", type="datetime")
	 * @Gedmo\Timestampable(on = "update")
     * @Assert\DateTime()
     */
    private $dateModification;

    /**
     * @var boolean
     *
     * @ORM\Column(name="delegue", type="boolean")
     */
    private $delegue;
	
	/**
	* @var \Doctrine\Common\Collections\Collection
	*
	* @ORM\ManyToMany(targetEntity="\CEC\SecteurProjetsBundle\Entity\Reunion", inversedBy="presents")
	*/
	private $reunions;

    /**
     * Groupe de tutorat fréquenté régulièrement par le membre.
     * Ce champ permet de définir le groupe de tutorat du membre ; in fine, cela lui permet d'accéder
     * au menu de tutorat avec les informations sur son groupe, soon/ses lycée(s), les prochaine séances,
     * le choix d'activité et la rédaction de compte-rendus.
     * Un tuteur appartenant à un groupe est considéré comme "actif" pour l'activité de tutorat. Il sera
     * comptabilisé dans les statistiques et recevra — si disponible — les notifications associées.
     *
     * @var CEC\TutoratBundle\Entity\Groupe
     *
     * @ORM\ManyToOne(targetEntity = "CEC\TutoratBundle\Entity\Groupe", inversedBy = "tuteurs" )
     */
    private $groupe;
	
	/**
     * Séances auxquelles le tuteur a participé.
     * Ce champ liste les séances de tutorat pour lesquelles le membre a indiqué qu'il participait.
     * Ceci sert principalement deux objectifs :
     *     - élaborer des statistiques avancées sur l'encadrement et le taux d'activité des tuteurs ;
     *     - faciliter l'organisation des séances en sachant le nombre de tuteurs disponibles.
     *
     * Il ne s'agit pas du côté propriétaire. Utilisez les méthodes de Seance pour
     * ajouter un tuteur à une séance.
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity = "CEC\TutoratBundle\Entity\Seance", mappedBy = "tuteurs" )
     */
    private $seances;

    /**
	*
	* Répertorie les sorties auxquelles s'est inscrit le lycéen.
	* ATTENTION : la suppression du lycéen supprime ses inscriptions.
	* 
	* Il ne s'agit pas du coté propriétaire. Utiliser les méthodes de Sorties pour ajouter un lycéen à une sortie.
	* 
	* @var \Doctrine\Common\Collections\Collection
    *
	*/
	private $sorties;

    /**
    *@var \Doctrine\Common\Collections\Collection
    *
    *@ORM\OneToMany(targetEntity="\CEC\SecteurProjetsBundle\Entity\ProjetEleve", mappedBy="lyceen")
    */
    private $projetsParAnnee;

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->getPrenom() . ' ' . $this->getNom();
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->getMotDePasse();
    }


    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }
	
	/**
     * Retourne la description d'un membre.
     * La description est le résultat de la concaténation du prénom et du nom.
     *
     * @return string Description du membre.
     */
    public function __toString()
    {

        return $this->getPrenom() . ' ' . $this->getNom();
    }

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
     * Set prenom
     *
     * @param string $prenom
     * @return Eleve
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Eleve
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Eleve
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }
	
	/**
     * Set telephone
     *
     * @param string $telephone
     * @return Membre
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }
	
	/**
	*
	* Vérifier si le telephone est public
	* @return boolean*/
	public function getTelephonePublic()
	{
		return $this->telephonePublic;
	}
	
	/**
	*
	* Change l'état du téléphone
	* @return boolean*/
	public function setTelephonePublic($boolean)
	{
		$this->telephonePublic = $boolean;
		return $this;
	}

    /**
     * Set lycee
     *
     * @param string $lycee
     * @return Eleve
     */
    public function setLycee($lycee)
    {
        $this->lycee = $lycee;
    
        return $this;
    }

    /**
     * Get lycee
     *
     * @return string 
     */
    public function getLycee()
    {
        return $this->lycee;
    }

    /**
     * Set classe
     *
     * @param string $classe
     * @return Eleve
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;
    
        return $this;
    }

    /**
     * Get classe
     *
     * @return string 
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set datenaiss
     *
     * @param \DateTime $datenaiss
     * @return Eleve
     */
    public function setDatenaiss($datenaiss)
    {
        $this->datenaiss = $datenaiss;
    
        return $this;
    }

    /**
     * Get datenaiss
     *
     * @return \DateTime 
     */
    public function getDatenaiss()
    {
        return $this->datenaiss;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return Eleve
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    /**
     * Get roles
     *
     * @return array 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Eleve
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
     * @return Eleve
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
     * Set delegue
     *
     * @param boolean $delegue
     * @return Eleve
     */
    public function setDelegue($delegue)
    {
        $this->delegue = $delegue;
    
        return $this;
    }

    /**
     * Get delegue
     *
     * @return boolean 
     */
    public function getDelegue()
    {
        return $this->delegue;
    }
	
	 /**
     * Set motDePasse
     *
     * @param string $motDePasse
     * @return Eleve
     */
    public function setMotDePasse($motDePasse)
    {
        $this->motDePasse = $motDePasse;
    
        return $this;
    }
	
	/**
     * Set groupe
     *
     * @param \CEC\TutoratBundle\Entity\Groupe $groupe
     * @return Membre
     */
    public function setGroupe(\CEC\TutoratBundle\Entity\Groupe $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \CEC\TutoratBundle\Entity\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Get motDePasse
     *
     * @return string 
     */
    public function getMotDePasse()
    {
        return $this->motDePasse;
    }
	
	/**
     * Set reunions
     *
     * @param array $reunions
     * @return Eleve
     */
    public function setReunions($reunions)
    {
        $this->motDePasse = $motDePasse;
    
        return $this;
    }
	
	/**
	* Add reunions
	*
	* @param \CEC\SecteurProjetsBundle\Entity\Reunion $reunion
	* @return Eleve
	*/
	public function addReunion( \CEC\SecteurProjetsBundle\Entity\Reunion $reunion)
	{
		$this->reunions[] = $reunion;
		return $this;
	}
	
	/**
     * Remove reunion
     *
     * @param \CEC\SecteurProjetsBundle\Entity\Reunion $reunion
     */
    public function removeReunion(\CEC\SecteurProjetsBundle\Entity\Reunion $reunion)
    {
        $this->secteurs->removeElement($reunion);
    }

    /**
     * Get reunions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReunions()
    {
        return $this->reunions;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reunions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projetsParAnnee = new \Doctrine\Common\Collections\ArrayCollection();
    }
    

    /**
     * Add projetsParAnnee
     *
     * @param \CEC\SecteurProjetsBundle\Entity\ProjetEleve $projetsParAnnee
     * @return Eleve
     */
    public function addProjetsParAnnee(\CEC\SecteurProjetsBundle\Entity\ProjetEleve $projetsParAnnee)
    {
        $this->projetsParAnnee[] = $projetsParAnnee;
    }

    /**
     * Remove projetsParAnnee
     *
     * @param \CEC\SecteurProjetsBundle\Entity\ProjetEleve $projetsParAnnee
     */
    public function removeProjetsParAnnee(\CEC\SecteurProjetsBundle\Entity\ProjetEleve $projetsParAnnee)
    {
        $this->projetsParAnnee->removeElement($projetsParAnnee);
    }

    /**
     * Get projetsParAnnee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjetsParAnnee()
    {
        return $this->projetsParAnnee;
    }
    
    /**
     * Add sorties
     *
     * @param \CEC\SecteurSortiesBundle\Entity\Sortie $sortie
     * @return Membre
     */
    public function addSortie(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
    {
        $this->sorties[] = $sortie;

        return $this;
    }

    
    /**
     * Remove sorties
     *
     * @param \CEC\SecteurSortiesBundle\Entity\Sortie $sortie
     */
    public function removeSortie(\CEC\SecteurSortiesBundle\Entity\Sortie $sortie)
    {
        $this->sorties->removeElement($sortie);
    }

    /**
     * Get sorties
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSorties()
    {
        return $this->sorties;
    }
}
