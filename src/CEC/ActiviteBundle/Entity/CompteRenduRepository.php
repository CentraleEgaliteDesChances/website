<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use CEC\ActiviteBundle\Entity\Activite;

/**
 * Permet de récupérer aisément diverses informations sur les compte-rendus.
 *
 * La présente classe Repository permet de faciliter les tâches suivantes :
 *     - récupérer la note moyenne d'une activité ;
 *
 * @author Jean-Baptiste Bayle
 * @version 1.0
 */
class CompteRenduRepository extends EntityRepository
{
    /**
     * Retourne la note moyenne globale pour une activité.
     * Calcule et retourne la note moyenne d'une activite (classe Activite),
     * obtenue à partir de l'ensemble des notes globales décernées dans les compte-rendus rédigés.
     * Retourne "null" si aucune note n'est disponible.
     *
     * @param CEC\ActiviteBundle\Entity\Activite $activite Activite dont on veut obtenir la note moyenne.
     * @return integer Note moyenne globale de l'activité.
     */
    public function getNoteMoyenneGlobalePourActivite(Activite $activite)
    {
        $compteRendus = $this->findByActivite($activite);
        $sommeDesNotes = 0;
        
        foreach ($compteRendus as $compteRendu) {
            $sommeDesNotes += $compteRendu->getNoteGlobale();
        }
        
        return count($compteRendus) > 0 ? $sommeDesNotes / count($compteRendus) : null;
    }
    
    /**
     * Retourne la note moyenne de contenu pour une activité.
     * Calcule et retourne la note moyenne d'une activite (classe Activite),
     * obtenue à partir de l'ensemble des notes de contenu décernées dans les compte-rendus rédigés.
     * Retourne "null" si aucune note n'est disponible.
     *
     * @param CEC\ActiviteBundle\Entity\Activite $activite Activite dont on veut obtenir la note moyenne.
     * @return integer Note moyenne de contenu de l'activité.
     */
    public function getNoteMoyenneContenuPourActivite(Activite $activite)
    {
        $compteRendus = $this->findByActivite($activite);
        $sommeDesNotes = 0;
        
        foreach ($compteRendus as $compteRendu) {
            $sommeDesNotes += $compteRendu->getNoteContenu();
        }
        
        return count($compteRendus) > 0 ? $sommeDesNotes / count($compteRendus) : null;
    }
    
    /**
     * Retourne la note moyenne d'interactivité pour une activité.
     * Calcule et retourne la note moyenne d'une activite (classe Activite),
     * obtenue à partir de l'ensemble des notes d'interactivité décernées dans les compte-rendus rédigés.
     * Retourne "null" si aucune note n'est disponible.
     *
     * @param CEC\ActiviteBundle\Entity\Activite $activite Activite dont on veut obtenir la note moyenne.
     * @return integer Note moyenne d'interactivité de l'activité.
     */
    public function getNoteMoyenneInteractivitePourActivite(Activite $activite)
    {
        $compteRendus = $this->findByActivite($activite);
        $sommeDesNotes = 0;
        
        foreach ($compteRendus as $compteRendu) {
            $sommeDesNotes += $compteRendu->getNoteInteractivite();
        }
        
        return count($compteRendus) > 0 ? $sommeDesNotes / count($compteRendus) : null;
    }
    
    /**
     * Retourne la note moyenne d'atteinte d'objectifs pour une activité.
     * Calcule et retourne la note moyenne d'une activite (classe Activite),
     * obtenue à partir de l'ensemble des notes d'atteinte d'objectifs décernées dans les compte-rendus rédigés.
     * Retourne "null" si aucune note n'est disponible.
     *
     * @param CEC\ActiviteBundle\Entity\Activite $activite Activite dont on veut obtenir la note moyenne.
     * @return integer Note moyenne d'atteinte d'objectifs de l'activité.
     */
    public function getNoteMoyenneAtteinteObjectifsPourActivite(Activite $activite)
    {
        $compteRendus = $this->findByActivite($activite);
        $sommeDesNotes = 0;
        
        foreach ($compteRendus as $compteRendu) {
            $sommeDesNotes += $compteRendu->getNoteAtteinteObjectifs();
        }
        
        return count($compteRendus) > 0 ? $sommeDesNotes / count($compteRendus) : null;
    }
}
