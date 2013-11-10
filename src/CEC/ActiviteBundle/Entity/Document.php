<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Un document représente une paire de fichiers enregistrés sur le serveur
 * et associés à une activité.
 *
 * Il s'agit d'une version du contenu de l'activité,
 * en deux formats : le format PDF permettant un téléchargement et une compatibilité étendue,
 * et le format original (Microsoft Word ou PowerPoint), permettant l'édition de l'activité si
 * nécessaire(comme par exemple si des corrections sont demandées dans un compte-rendu de l'activité).
 *
 * La classe gère la gestion des fichiers sur le serveur, et, si nécessaire et disponible,
 * la conversion au format PDF grâce au site tierce http://www.conv2pdf.com.
 * On notera donc que pour créer un nouveau document, il suffit de fournir un fichier Word
 * si la conversion est disponible ; le fichier PDF sera alors automatiquement généré.
 *
 * Il est important de noter que deux documents ne peuvent avoir le même nom sur le serveur ;
 * c'est pourquoi nomFichierPDF et nomFichierOriginal doivent être uniques. Un historique des changements
 * d'une activité est conservé grâce au champ "description" de chaque document.
 *
 * Lorsqu'un objet Document est supprimé, les fichiers associés sont eux-aussi définitivement détruit
 * sur le serveur. Lorsqu'une activité est supprimée, tous les documents associés (versions) sont supprimés.
 *
 * IMPORTANT : dans la version 1.0, la génération automatique de PDF à partir du fichier Word
 *             n'est pas fonctionnelle. Un fichier PDF doit donc obligatoirement être fourni
 *             par l'utilisateur lors de la création d'un document.
 *             La méthode genererFichierPDF renvoit donc "false" par défaut, et le champ
 *             fichierPDF est requis ; pour activer la génération automatique de PDF, il suffit
 *             d'implémenter genererFichierPDF et de désactiver Assert\NotBlank() pour fichierPDF.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields = "nomFichierPDF",
 *     message = "Un fichier PDF possédant le même nom est déjà présent sur le serveur. Merci de ré-essayer."
 * )
 * @UniqueEntity(
 *     fields = "nomFichierOriginal",
 *     message = "Un fichier original possédant le même nom est déjà présent sur le serveur. Merci de ré-essayer."
 * )
 */
class Document
{
    /**
     * Description pour la première version d'une activité.
     * @var string
     */
    const DocumentDescriptionPremiereVersion = "Première version de l'activité";

    /**
     * @var integer
     *
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     */
    private $id;

    /**
     * Brève description des nouveautés du document.
     * Ce champ permet de conserver un historique des changements des versions d'une activité.
     * Pour chaque nouveau document (donc nouvelle version) d'une activité (classe Activite),
     * un court descriptif des changements effectué est enregistré.
     * Cette chaîne de texte n'est donc pas requise mais est limitée à 255 caractères.
     *
     * @var string
     *
     * @ORM\Column(name = "description", type = "string", length = 255, nullable = true)
     * @Assert\MaxLength(
     *     limit = 255,
     *     message = "La description du document ne peut excéder 255 caractères."
     * )
     */
    private $description;

    /**
     * Fichier original téléchargé.
     * Cet attribut permet de générer un champ de téléchargement de fichier dans un formulaire
     * lors de la création du document. Il doit correspondre au fichier original, et est donc requis.
     * Il est important de noter que seules les extensions Microsoft Word (.doc et .docx), ainsi que celle
     * Microsoft PowerPoint (.ppt et .pptx) sont acceptées, et que la taille du fichier ne peut excéder 10 Mo.
     *
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize = "10240k",
     *     maxSizeMessage = "La taille du fichier original ne peut excéder 10 Mo.",
     *     mimeTypes = { "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation" },
     *     mimeTypesMessage = "Le fichier original doit être sous format Microsoft Word (.doc ou .docx) ou sous le format Microsoft PowerPoint (.ppt ou .pptx)."
     * )
     * @Assert\NotBlank(message = "Un fichier original est requis !")
     */
    private $fichierOriginal;

    /**
     * Fichier PDF téléchargé.
     * Cet attribut permet de générer un champ de téléchargement de fichier dans un formulaire
     * lors de la création du document. Il doit correspondre au fichier PDF, et n'est donc pas requis.
     * En effet, si le service est disponible, on peut convertir le fichier original au format PDF et l'utiliser.
     * Il est important de noter que seule l'extension .pdf est accepté, et que la
     * taille du fichier ne peut excéder 10 Mo.
     *
     * IMPORTANT : dans la version 1.0, la génération automatique de PDF n'est pas disponible.
     *             Le champ fichierPDF est donc requis.
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
     * Représente le nom du fichier original, avec son extension.
     * Il permet d'accéder par la suite au fichier stocké sur le serveur.
     * Il s'agit d'une chaîne de caractères de moins de 50 caractères, qui doit être unique.
     *
     * @var string
     *
     * @ORM\Column(name = "nomFichierOriginal", type = "string", length = 50)
     * @Assert\MaxLength(
     *     limit = 50,
     *     message = "Le nom du fichier original ne peut excéder 50 caractères."
     * )
     */
    private $nomFichierOriginal;

    /**
     * Représente le nom du fichier PDF, avec son extension.
     * Il permet d'accéder par la suite au fichier stocké sur le serveur.
     * Il s'agit d'une chaîne de caractères de moins de 50 caractères, qui doit être unique.
     *
     * @var string
     *
     * @ORM\Column(name = "nomFichierPDF", type = "string", length = 50)
     * @Assert\MaxLength(
     *     limit = 50,
     *     message = "Le nom du fichier PDF ne peut excéder 50 caractères."
     * )
     */
    private $nomFichierPDF;

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
     * Activité associée au document.
     * Le présent document représente donc une version de l'activité (classe Activite)
     * et fait partie de l'historique de celle-ci.
     *
     * @var Activite
     *
     * @ORM\ManyToOne(targetEntity = "Activite", inversedBy = "versions")
     * @Assert\NotBlank(message = "Le document doit être associé à une activité.")
     */
    private $activite;

    /**
     * Membre auteur du document.
     * Il est enregistré lors de l'ajout du document et permet de garder une trace de l'activité du membre.
     *
     * @var CEC\MembreBundle\Entity\Membre
     *
     * @ORM\ManyToOne(targetEntity = "CEC\MembreBundle\Entity\Membre", inversedBy = "documents")
     * @Assert\NotBlank(message = "Le document doit être associé à un auteur.")
     */
    private $auteur;


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
     * Retourne le chemin relatif du fichier original.
     *
     * @return string Chemin relatif du fichier original.
     */
    public function getCheminOriginal()
    {
        return $this->getNomFichierOriginal() ? $this->getDossierTelechargement() . '/' . $this->getNomFichierOriginal() : null;
    }

    /**
     * Retourne le chemin absolu du fichier original.
     *
     * @return string Chemin absolu du fichier original.
     */
    public function getCheminAbsoluOriginal()
    {
        return $this->getNomFichierOriginal() ? $this->getDossierRacineTelechargement() . '/' . $this->getNomFichierOriginal() : null;
    }

    /**
     * Retourne le chemin relatif du dossier de téléchargement des documents.
     *
     * @return string Chemin relatif du dossier de téléchargement des documents.
     */
    public function getDossierTelechargement()
    {
        return '/uploads/documents';
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
     * Génère les noms des fichiers original et PDF.
     * Cette méthode génère aléatoirement un nom pour les fichiers original et PDF.
     * Elle est appelée avant la persistance de l'entité (et avant sa mise-à-jour).
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function genererNomsFichiers()
    {
        if (null !== $this->fichierPDF)
        {
            $this->setNomFichierPDF(uniqid() . '.' . $this->fichierPDF->guessExtension());
        }

        if (null !== $this->fichierOriginal)
        {
            $this->setNomFichierOriginal(uniqid() . '.' . $this->fichierOriginal->guessExtension());
        }
    }

    /**
     * Déplace les fichiers sur le serveur, et génère le fichier PDF si besoin est.
     * Cette méthode est appelée après la persistance et la mise à jour de l'entité.
     *
     * ATTENTION : dans la version 1.0, la génération automatique du fichier PDF n'est pas
     *             fonctionnelle.
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function telecharger()
    {
        if ($this->fichierOriginal !== null)
        {
            $this->fichierOriginal->move($this->getDossierRacineTelechargement(), $this->getNomFichierOriginal());
            unset($this->fichierOriginal);
        }

        if ($this->fichierPDF !== null)
        {
            $this->fichierPDF->move($this->getDossierRacineTelechargement(), $this->getNomFichierPDF());
            unset($this->fichierPDF);
        }
        else
        {
            $this->genererFichierPDF();
        }
    }

    /**
     * Génère le fichier PDF à partir du fichier original associé à l'entité.
     * Ne fait rien si aucun fichier Word n'existe. Le fichier PDF généré est déplacé sur le serveur
     * suivant l'attribut $nomFichierPDF.
     *
     * Pour la génération, on utilise le site tierce http://www.conv2pdf.com, qui converti sur un serveur
     * les documents Word en PDF. On télécharge ensuite le résultat.
     *
     * @return false
     */
    public function genererFichierPDF()
    {
        return false;
    }

    /**
     * Vérifie l'existence des documents sur le serveur.
     * Cette méthode renvoie "true" si le fichier Original ET le fichier PDF se trouvent sur le serveur.
     * Dans le cas contraire, on renvoie "false".
     *
     * @return boolean Les fichiers existent-ils sur le serveur ?
     */
    public function getDisponible()
    {
        return is_file($this->getCheminAbsoluPDF()) and is_file($this->getCheminAbsoluOriginal());
    }

    /**
     * Supprime la paire de fichiers associée du serveur.
     * Cette méthode est appelée à la suite de la suppression du document dans la base de donnée.
     * En particulier, elle est appelée lorsque l'activité (classe Activite) associée au document
     * est elle-même supprimée.
     *
     * @ORM\PostRemove
     */
    public function supprimer()
    {
        if ($fichier = $this->getCheminAbsoluPDF()) {
            if (is_file($fichier)) unlink($fichier);
        }

        if ($fichier = $this->getCheminAbsoluOriginal()) {
            if (is_file($fichier)) unlink($fichier);
        }
    }

    /**
     * Description du document.
     * Renvoit la description de l'activité associée et la date du document.
     *
     * @return string Description dun document.
     */
    public function __toString()
    {
        return $this->getActivite() . ' - ' . $this->getDateCreation();
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
     * Set description
     *
     * @param string $description
     * @return Document
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set fichierOriginal
     *
     * @param Symfony\Component\HttpFountdation\File\UploadedFile $fichierOriginal
     * @return Document
     */
    public function setFichierOriginal(UploadedFile $fichierOriginal)
    {
        $this->fichierOriginal = $fichierOriginal;

        return $this;
    }

    /**
     * Get fichierOriginal
     *
     * @return Symfony\Component\HttpFountdation\File\UploadedFile
     */
    public function getFichierOriginal()
    {
        return $this->fichierOriginal;
    }

    /**
     * Set fichierPDF
     *
     * @param Symfony\Component\HttpFountdation\File\UploadedFile $fichierPDF
     * @return Document
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
     * @return Document
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
     * Set nomFichierOriginal
     *
     * @param string $nomFichierOriginal
     * @return Document
     */
    public function setNomFichierOriginal($nomFichierOriginal)
    {
        $this->nomFichierOriginal = $nomFichierOriginal;

        return $this;
    }

    /**
     * Get nomFichierOriginal
     *
     * @return string
     */
    public function getNomFichierOriginal()
    {
        return $this->nomFichierOriginal;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
