<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Représente un quizz actu, incluant le fichier pdf associé.
 * Généralement, le secteur actis culturelles sort un quizz actu par semaine.
 * Pour cela l'entité est caractérisé par sa semaine, représentée par la date du lundi (à ne pas confondre avec la date de création).
 *
 * Contrairement aux documents des activités, il n'y a pas de gestion de version pour les quizz actu ni de document original.
 * Il est important de noter que deux quizz actu ne peuvent avoir le même nom sur le serveur.
 * Le quizz actu est relié à son auteur, la suppression de l'auteur supprimera tous les quizz actu dont il est l'auteur.
 *
 * @author Corentin Bertrand
 * @version 1.0
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CEC\ActiviteBundle\Entity\QuizzActuRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields = "semaine",
 *     message = "Un quizz actu existe déjà pour cette semaine."
 * )
 * @UniqueEntity(
 *     fields = "nomFichierPDF",
 *     message = "Un fichier PDF possédant le même nom est déjà présent sur le serveur. Merci de ré-essayer."
 * )
 */
class QuizzActu
{
    /**
     * @var integer
     *
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * Semaine du quizz actu. C'est la semaine durant laquelle ce quizz actu doit être utilisé en séance.
     * Elle est repérée par la date du lundi de la semaine en question.
     *
     * @var \DateTime
     *
     * @ORM\Column(name = "semaine", type = "datetime")
     * @Assert\DateTime()
     */
    private $semaine;

    /**
     * Commentaire facultatif sur le quizz actu, en moins de 200 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "commentaire", type = "text", nullable = true)
     * @Assert\Length(
     *     max = 200,
     *     maxMessage = "Le commentaire ne peut excéder 200 caractères."
     * )
     */
    private $commentaire;

    /**
     * Fichier PDF téléchargé.
     * Cet attribut permet de générer un champ de téléchargement de fichier dans un formulaire
     * lors de la création du quizz actu.
     * Il est important de noter que seule l'extension .pdf est accepté, et que la
     * taille du fichier ne peut excéder 10 Mo.
     *
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize = "10240k",
     *     maxSizeMessage = "La taille du fichier PDF ne peut dépasser 10 Mo.",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Le fichier PDF doit être sous format Adobe PDF (.pdf)."
     * )
     * @Assert\NotBlank(message = "Un fichier PDF est requis !")
     */
    private $fichierPDF;

    /**
     * Représente le nom du fichier PDF, avec son extension.
     * Il permet d'accéder par la suite au fichier stocké sur le serveur.
     * Il s'agit d'une chaîne de caractères de moins de 50 caractères, qui doit être unique.
     *
     * @var string
     *
     * @ORM\Column(name = "nomFichierPDF", type = "string", length = 50)
     * @Assert\Length(
     *     max = 50,
     *     maxMessage = "Le nom du fichier PDF ne peut excéder 50 caractères."
     * )
     */
    private $nomFichierPDF;


    /**
     * Membre auteur du document.
     *
     * @var CEC\MembreBundle\Entity\Membre
     *
     * @ORM\ManyToOne(targetEntity = "CEC\MembreBundle\Entity\Membre", inversedBy = "quizzActus")
     * @Assert\NotBlank(message = "Le quizz actu doit être associé à un auteur.")
     */
    private $auteur;

    /**
     * Date de création.
     *
     * @var \DateTime
     *
     * @ORM\Column(name = "dateCreation", type = "datetime")
     * @Gedmo\Timestampable(on = "create")
     * @Assert\DateTime()
     */
    private $dateCreation;

    /**
     * Date de dernière modification.
     *
     * @var \DateTime
     *
     * @ORM\Column(name = "dateModification", type = "datetime")
     * @Gedmo\Timestampable(on = "update")
     * @Assert\DateTime()
     */
    private $dateModification;


    /**
     * Renvoi true si l'attribut semaine correspond à un lundi, false sinon.
     * Sert à valider le formulaire du quizz actu.
     *
     * @Assert\isTrue(message = "Cette date ne correspond pas au premier jour d'une semaine.")
     */
    public function isSemaineValid()
    {
        return $this->semaine->format('N') == 1;
    }

    /**
     * Retourne le chemin relatif du fichier PDF.
     * Si aucun fichier PDF n'existe, on renvoie "null".
     *
     * @return string Chemin relatif du fichier PDF.
     */
    public function getCheminPDF()
    {
        return $this->getNomFichierPDF() ? $this->getDossierTelechargement() . '/' . $this->getNomFichierPDF() : null;
    }

    /**
     * Retourne le chemin absolu du fichier PDF.
     * Si aucun fichier PDF n'existe, on renvoie "null".
     *
     * @return string Chemin absolu du fichier PDF.
     */
    public function getCheminAbsoluPDF()
    {
       return $this->getNomFichierPDF() ? $this->getDossierRacineTelechargement() . '/' . $this->getNomFichierPDF() : null;
    }

    /**
     * Retourne le chemin relatif du dossier de téléchargement des documents.
     *
     * @return string Chemin relatif du dossier de téléchargement des documents.
     */
    public function getDossierTelechargement()
    {
        return '/uploads/quizzActus';
    }

    /**
     * Retourne le chemin absolu du dossier de téléchargement des documents.
     *
     * @return string Chemin absolu du dossier de téléchargement des documents.
     */
    public function getDossierRacineTelechargement()
    {
        $dossierTelechargement = $this->getDossierTelechargement();
        return __DIR__ . '/../../../../web' . $dossierTelechargement;
    }

    /**
     * Génère le nom du fichier PDF.
     * Cette méthode génère le nom du fichier PDF à partir de l'id du quizz actu.
     * Elle est appelée avant la persistance de l'entité.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function genererNomsFichiers()
    {
        if (null !== $this->fichierPDF)
        {
            $this->setNomFichierPDF($this->semaine->format('d-m-Y') . '.' . $this->fichierPDF->guessExtension());
        }
    }

    /**
     * Déplace le fichier sur le serveur.
     * Cette méthode est appelée après la persistance de l'entité.
     *
     * @ORM\PostPersist
     * @ORM\PreUpdate
     */
    public function telecharger()
    {
        if ($this->fichierPDF !== null)
        {
            $this->fichierPDF->move($this->getDossierRacineTelechargement(), $this->getNomFichierPDF());
            unset($this->fichierPDF);
        }
    }

    /**
     * Vérifie l'existence du document sur le serveur.
     * Cette méthode renvoie "true" si le fichier PDF se trouve sur le serveur.
     * Dans le cas contraire, on renvoie "false".
     *
     * @return boolean Le fichier existe-t-il sur le serveur ?
     */
    public function getDisponible()
    {
        return is_file($this->getCheminAbsoluPDF());
    }

    /**
     * Supprime le fichier du serveur.
     * Cette méthode est appelée à la suite de la suppression du quizz actu dans la base de donnée.
     *
     * @ORM\PostRemove
     */
    public function supprimer()
    {
        if ($fichier = $this->getCheminAbsoluPDF()) {
            if (is_file($fichier)) unlink($fichier);
        }
    }

    /**
     * Description du quizz actu.
     * Renvoit la semaine du quizz actu.
     *
     * @return string Description dun document.
     */
    public function __toString()
    {
        $mois = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'décembre');

        $return = 'Semaine du ' . $this->semaine->format('j') . ' au ' . $this->semaine->modify('+6 days')->format('j') . ' ' . $mois[$this->semaine->format('n')-1] . ' ' . $this->semaine->format('Y');

        // Comme modify() modifie l'objet semaine, il faut corriger le décalage crée au cas ou l'objet semaine serai réutilisé
        $this->semaine->modify('-6 days');

        return $return;
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
     * Set semaine
     *
     * @param \DateTime $semaine
     * @return QuizzActu
     */
    public function setSemaine($semaine)
    {
        $this->semaine = $semaine;

        return $this;
    }

    /**
     * Get semaine
     *
     * @return \DateTime
     */
    public function getSemaine()
    {
        return $this->semaine;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return QuizzActu
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set fichierPDF
     *
     * @param Symfony\Component\HttpFountdation\File\UploadedFile $fichierPDF
     * @return QuizzActu
     */
    public function setFichierPDF(UploadedFile $fichierPDF)
    {
        $this->fichierPDF = $fichierPDF;

        return $this;
    }

    /**
     * Get fichierPDF
     *
     * @return Symfony\Component\HttpFountdation\File\UploadedFile
     */
    public function getFichierPDF()
    {
        return $this->fichierPDF;
    }

    /**
     * Set nomFichierPDF
     *
     * @param string $nomFichierPDF
     * @return QuizzActu
     */
    public function setNomFichierPDF($nomFichierPDF)
    {
        $this->nomFichierPDF = $nomFichierPDF;

        return $this;
    }

    /**
     * Get nomFichierPDF
     *
     * @return string
     */
    public function getNomFichierPDF()
    {
        return $this->nomFichierPDF;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return QuizzActu
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
     * @return QuizzActu
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
     * Set auteur
     *
     * @param \CEC\MembreBundle\Entity\Membre $auteur
     * @return QuizzActu
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
}
