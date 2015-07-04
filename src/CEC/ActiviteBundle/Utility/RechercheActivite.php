<?php

namespace CEC\ActiviteBundle\Utility;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use CEC\TutoratBundle\Entity\Groupe;

/**
 * Représente une recherche d'activité.
 * La classe contient les paramètres permettant de filtrer les résultats :
 *     - recherche dans le titre ;
 *     - recherche par type d'activité ;
 *     - masquage des activités déjà réalisées ;
 *     - filtre par tags.
 * A noter qu'un champ vide correspond à un filtre inactif.
 * On transmet le groupe de l'utilisateur pour permettre le filtrage des activités réalisées.
 *
 * Cette classe n'est pas persistée, mais est utilisée dans le formulaire RechercheActiviteType.
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 */
 
/**
 * @Assert\Callback( methods = { "isGroupeValid" } )
 */
class RechercheActivite
{
    /**
     * Recherche dans le titre de l'activité.
     * 
     * @var string
     * @Assert\Type(
     *     type = "string",
     *     message = "La recherche par titre ne fonctionne qu'avec du texte."
     * )
     */
    private $titre;
    
    /**
     * Filtrage par type d'activité.
     * À choisir parmi les valeurs suivantes :
     * Activité Culturelle, Activité Scientifique, Expérience Scientifique et Autre.
     * 
     * @var string
     * @Assert\Choice(
     *     choices = {"Activité Culturelle", "Activité Scientifique", "Expérience Scientifique", "Autre"},
     *     message = "Le type d'activité choisi n'est pas valide."
     * )
     */
    private $type;
    
    /**
     * Filtrage des activités déjà réalisées.
     * Si "true", on doit masquer les activités déjà réalisées par le groupe de tutorat
     * du membre qui consulte le site.
     * 
     * @var boolean
     * @Assert\Type(
     *     type = "bool",
     *     message = "Le filtrage des activités utilisées ne peut prendre que deux valeurs : vrai ou faux."
     * )
     */
    private $filtrerActivitesRealisees;
    
    /**
     * Groupe de tutorat de l'utilisateur.
     * Ce champ permet, lorsque l'option $filtrerActivitesRealisees est choisie, de filtrer
     * les résultats de la recherche suivant le groupe de tutorat de l'utilisateur.
     * 
     * @var CEC\TutoratBundle\Entity\Groupe;
     */
    private $groupe;
    
    
    public function getTitre() {
        return $this->titre;
    }
    public function getType() {
        return $this->type;
    }
    public function getFiltrerActivitesRealisees() {
        return $this->filtrerActivitesRealisees;
    }
    public function getGroupe() {
        return $this->groupe;
    }
    
    public function setTitre($titre) {
        $this->titre = $titre;
        return $this;
    }
    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    public function setFiltrerActivitesRealisees($filtrerActivitesRealisees) {
        $this->filtrerActivitesRealisees = $filtrerActivitesRealisees;
        return $this;
    }
    public function setGroupe(Groupe $groupe) {
        $this->groupe = $groupe;
        return $this;
    }
    
    /**
     * Permet la validation du groupe.
     * Le groupe doit être non nul dès que l'option $filtrerActivitesRealisees est choisie.
     */
    public function isGroupeValid(ExecutionContext $context) {
        if ($this->getFiltrerActivitesRealisees() &&
                ( $this->getGroupe() == NULL || $this->getGroupe() == '' || is_null($this->getGroupe()) )
            ) {
            // On ajout une violation de contrainte
            $context->addViolation('Vous devez appartenir à un groupe pour filtrer les activités déjà réalisées dans votre groupe !');
        }
    }
}
