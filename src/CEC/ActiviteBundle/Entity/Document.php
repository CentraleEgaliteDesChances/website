<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFountdation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Document
 * (Jean-Baptiste Bayle — Mai 2013)
 *
 * Un document représente une paire de fichiers enregistrés sur le serveur
 * et associée à une activité. Il s'agit d'une version du contenu de l'activité, 
 * en deux formats : le format PDF permettant un téléchargement et une compatibilité
 * étendue, et le format Microsoft Word, permettant l'édition de l'activité si nécessaire
 * (comme par exemple si des corrections sont demandées dans un compte-rendu de l'activité).
 *
 * La classe gère la gestion des fichiers sur le serveur, et, si nécessaire et disponible,
 * la conversion au format PDF grâce au site tierce http://www.conv2pdf.com.
 * On notera donc que pour créer un nouveau document, il suffit de fournir un fichier Word.
 *
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Document
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
     * Fichier Word téléchargé.
     * Cet attribut permet de générer un champ de téléchargement de fichier dans un formulaire
     * lors de la création du document. Il doit correspondre au fichier Word, et est donc requis.
     * Il est important de noter que seules les extensions .doc et .docx sont acceptés, et que la 
     * taille du fichier ne peut excéder 1 Mo.
     *
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage = "La taille du fichier ne peut dépasser 1 Mo.",
     *     mimeTypes = { "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document" },
     *     mimeTypesMessage = "Le fichier doit être sous format Microsoft Word (.doc ou .docx)."
     * )
     * @Assert\NotBlank()
     */
    private $fichierWord;
    
    /**
     * Fichier PDF téléchargé.
     * Cet attribut permet de générer un champ de téléchargement de fichier dans un formulaire
     * lors de la création du document. Il doit correspondre au fichier PDF, et n'est donc pas requis.
     * En effet, si le service est disponible, on peut convertir le fichier Word au format PDF et l'utiliser.
     * Il est important de noter que seules l'extension .pdf est accepté, et que la 
     * taille du fichier ne peut excéder 1 Mo.
     *
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage = "La taille du fichier ne peut dépasser 1 Mo.",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Le fichier doit être sous format Adobe PDF (.pdf)."
     * )
     * @Assert\NotBlank()
     */
    private $fichierPDF;

    /**
     * Représente le nom du fichier PDF, avec son extension.
     * Il permet d'accéder par la suite au fichier stocké sur le serveur.
     *
     * @var string
     *
     * @ORM\Column(name="fichierPDF", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nomFichierPDF;

    /**
     * Représente le nom du fichier Word, avec son extension.
     * Il permet d'accéder par la suite au fichier stocké sur le serveur.
     *
     * @var string
     *
     * @ORM\Column(name="fichierWord", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nomFichierWord;

    /**
     * Date de création.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     * @Assert\NotBlank()
     */
    private $dateCreation;

    /**
     * Date de dernière modification.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="dateModification", type="datetime")
     * @Assert\NotBlank()
     */
    private $dateModification;
    
    /**
     * Activité associée au document. Le présent document représente donc
     * une version de l'activité et fait partie de l'historique de celle-ci.
     *
     * @var Activite
     *
     * @ORM\ManyToOne(targetEntity="Activite", inversedBy="versions")
     * @Assert\NotBlank()
     */
    private $activite;
    
    /**
     * Membre auteur du document. Il est enregistré lors de l'ajout du document
     * et permet de garder une trace de l'activité du membre.
     *
     * @var CEC\MembreBundle\Entity\Membre
     *
     * @ORM\ManyToOne(targetEntity="CEC\MembreBundle\Entity\Membre", inversedBy="documents")
     * @Assert\NotBlank()
     */
    private $auteur;
    
    
    /**
     * Retourne le chemin absolu du fichier PDF.
     * Si aucun fichier PDF n'existe, on renvoit le chemin du fichier Word associé.
     *
     * @return string
     */
    public function getCheminPDF()
    {
        if ($this->getNomFichierPDF()) { 
            return $this->getDossierTelechargement() . '/' . $this->getNomFichierPDF();
        } else {
            return $this->getCheminWord();
        }
    }
    
    /**
     * Retourne le chemin absolu du fichier Word.
     *
     * @return string
     */
    public function getCheminWord()
    {
        return $this->getNomFichierWord() ? null : $this->getDossierTelechargement() . '/' . $this->getNomFichierWord();
    }
    
    /**
     * Retourne le chemin du dossier de téléchargement des documents.
     *
     * @return string
     */
    public function getDossierTelechargement()
    {
        $dossierTelechargement = 'uploads/documents';
        return __DIR__ . '/../../../../web/' . $dossierTelechargement;
    }
    
    /**
     * Défini les noms des fichiers Word et PDF avant la persistence de l'entité.
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function genererNomsFichiers()
    {
        if (null !== $this->fichierPDF)
        {
            $this->setNomFichierPDF(uniqid() . '.' . $this->fichierPDF->guessExtension());
        }
        
        if (null !== $this->fichierWord)
        {
            $this->setNomFichierWord(uniqid() . '.' . $this->fichierWord->guessExtension());
        }
    }
    
    /**
     * Après la persistence et la mise à jour de l'entité :
     * déplace les fichiers sur le serveur, et génère le fichier PDF si besoin est.
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function telechargement()
    {
        if ($this->fichierWord !== null)
        {
            $$this->fichierWord->move($this->getDossierTelechargement(), $this->getNomFichierWord());
            unset($this->fichierWord);
        }
        
        if ($this->fichierPDF !== null)
        {
            $this->fichierPDF->move($this->getDossierTelechargement(), $this->getNomFichierPDF());
            unset($this->fichierPDF);
        }
        else
        {
            $this->genererFichierPDF();
        }
    }
    
    /**
     * Génère le fichier PDF à partir du fichier Word associé à l'entité.
     * Ne fait rien si aucun fichier Word n'existe. Le fichier PDF généré est déplacé sur le serveur
     * suivant l'attribut $nomFichierPDF.
     *
     * Pour la génération, on utilise le site tierce http://www.conv2pdf.com, qui converti sur un serveur
     * les documents Word en PDF. On télécharge ensuite le résultat. 
     *
     * @return string
     */
    public function genererFichierPDF()
    {
        // TODO
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
     * Set nomFichierWord
     *
     * @param string $nomFichierWord
     * @return Document
     */
    public function setNomFichierWord($nomFichierWord)
    {
        $this->nomFichierWord = $nomFichierWord;
    
        return $this;
    }

    /**
     * Get nomFichierWord
     *
     * @return string 
     */
    public function getNomFichierWord()
    {
        return $this->nomFichierWord;
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