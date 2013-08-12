<?php

namespace CEC\ActiviteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use CEC\ActiviteBundle\Entity\Activite;
use CEC\TutoratBundle\Entity\Groupe;

/**
 * Permet de récupérer aisément diverses informations sur les compte-rendus.
 *
 * La présente classe Repository permet de faciliter les tâches suivantes :
 *     - récupérer la note moyenne d'une activité (note globale, de contenu, d'interactivité,
 *       et d'atteinte des objectifs) ;
 *     - récupérer le dernier compte-rendu d'une activité ;
 *     - récupérer tous les compte-rendus à rédiger pour un groupe de tutorat.
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
        $compteRendus = array_filter($compteRendus, function (CompteRendu $compteRendu) {
            return $compteRendu->isRedige();
        });
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
        $compteRendus = array_filter($compteRendus, function (CompteRendu $compteRendu) {
            return $compteRendu->isRedige();
        });
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
        $compteRendus = array_filter($compteRendus, function (CompteRendu $compteRendu) {
            return $compteRendu->isRedige();
        });
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
        $compteRendus = array_filter($compteRendus, function (CompteRendu $compteRendu) {
            return $compteRendu->isRedige();
        });
        $sommeDesNotes = 0;
        
        foreach ($compteRendus as $compteRendu) {
            $sommeDesNotes += $compteRendu->getNoteAtteinteObjectifs();
        }
        
        return count($compteRendus) > 0 ? $sommeDesNotes / count($compteRendus) : null;
    }
    
    /**
     * Retourne le dernier compte-rendu d'une activité.
     * Si aucun compte-rendu n'existe, une exception est levée.
     *
     * @param CEC\ActiviteBundle\Entity\Activite $activite Activite dont on veut obtenir le dernier compte-rendu.
     * @return CEC\ActiviteBundle\Entity\CompteRendu Dernier compte-rendu.
     */
    public function getDernierPourActivite(Activite $activite)
    {
        return $this->findOneBy(
            array('activite' => $activite->getId()),
            array('dateModification' => 'ASC'),
            1);
    }
    
    /**
     * Retourne tous les comptes-rendus à rédiger d'un groupe de tutorat.
     * On retourne les comptes-rendus dont les séances ont déjà débutées et qui ne sont pas rédigés.
     * A noter qu'on ne sélectionne pas les comptes-rendus plus vieux de 2 mois après le début de la séance.
     *
     * @param CEC\TutoratBundle\Entity\Groupe $groupe Groupe de tutorat dont on veut les compte-rendus.
     * @return array Compte-rendus à rédiger pour le groupe.
     */
    public function findARedigerByGroupe(Groupe $groupe)
    {
        $query = $this->createQueryBuilder('cr')
            ->join('cr.seance', 's')
            ->where('cr.seance = s.id')
            ->andWhere('s.groupe = :groupe_id')
            ->andWhere("s.date BETWEEN DATE_SUB(CURRENT_DATE(), 2, 'MONTH') AND CURRENT_DATE()")
            ->orderBy('cr.dateCreation', 'DESC')
            ->setParameter('groupe_id', $groupe->getId())
            ->getQuery();
         $resultats = $query->getResult();
         $resultats = array_filter($resultats, function (CompteRendu $compteRendu) {
             return !$compteRendu->isRedige();
         });
         return $resultats;
    }
    
    /**
     * Retourne les comptes-rendus non-lus d'un certain type.
     * On retourne les comptes-rendus dont les activités sont du type indiqué (Tous, Activité Scientifique,
     * Activité Culturelle, Expérience Scientifique ou Autres). Si le deuxième argument est égal à "false",
     * on renvoit aussi les compte-rendus qui ont été marqués comme lus.
     *
     * @param string $type Type des activités dont on veut les comptes-rendus
     * @param boolean $nonLu Ne veut-on que les comptes-rendus non-lus ?
     * @return array Compte-rendus répondant aux critères.
     */
    public function findNonLusWithType($type = null, $nonLu = true)
    {
        $query = $this->createQueryBuilder('cr');
        if ($type) {
            $query->join('cr.activite', 'a')
                ->where('cr.activite = a.id')
                ->andWhere('a.type = :type')
                ->setParameter('type', $type);
        }
        if ($nonLu) $query->andWhere('cr.lu = FALSE');
        $query->orderBy('cr.dateModification', 'DESC');
            
         return $query->getQuery()->getResult();
    }
}
