<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\MainBundle\Classes\AnneeScolaire;
use CEC\TutoratBundle\Entity\Lycee;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\TutoratBundle\Entity\ChangementCordeeLycee;
use CEC\TutoratBundle\Entity\ChangementEnseignantLycee;
use CEC\TutoratBundle\Entity\ChangementLyceeVP;
use CEC\TutoratBundle\Entity\ChangementGroupeTuteur;
use CEC\TutoratBundle\Entity\ChangementGroupeLyceen;

class LyceesController extends Controller
{
    /**
     * Helper
     * Retourne la liste des cordées actuelles d'un lycée.
     *
     * @param Lycee $lycee: lycée
     * @return array(Cordee)
     */
    public function getCordeesForLycee(Lycee $lycee)
    {
        // On considère tous les changements de partenariat pour ce lycée
        $changements = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:ChangementCordeeLycee')
            ->findByLycee($lycee);
        
        // On effectue les modifications
        $cordees = array();
        foreach ($changements as $changement) {
            if ($changement->getAction() == ChangementCordeeLycee::CHANGEMENT_ACTION_AJOUT) {
                $cordees = array_merge($cordees, array($changement->getCordee()));
            } elseif ($changement->getAction() == ChangementCordeeLycee::CHANGEMENT_ACTION_SUPPRESSION) {
                $cordees = array_diff($cordees, array($changement->getCordee()));
            }
        }
        
        return $cordees;
    }
    
    /**
     * Helper
     * Retourne la liste des VP Lycées actuels d'un lycée.
     *
     * @param Lycee $lycee: lycée
     * @return array(Membre)
     */
    public function getVPsForLycee(Lycee $lycee)
    {
        // On considère tous les changements de VP Lycée pour ce lycée
        $changements = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:ChangementLyceeVP')
            ->findByLycee($lycee);
        
        // On effectue les modifications
        $VPs = array();
        foreach ($changements as $changement) {
            if ($changement->getAction() == ChangementLyceeVP::CHANGEMENT_ACTION_AJOUT) {
                $VPs = array_merge($VPs, array($changement->getVP()));
            } elseif ($changement->getAction() == ChangementLyceeVP::CHANGEMENT_ACTION_SUPPRESSION) {
                $VPs = array_diff($VPs, array($changement->getVP()));
            }
        }
        
        return $VPs;
    }
    
    /**
     * Helper
     * Retourne la liste des tuteurs actuels d'un groupe de tutorat
     *
     * @param Groupe $groupe: groupe de tutorat
     * @return array(Membre)
     */
    public function getTuteursForGroupe(Groupe $groupe)
    {
        // On considère tous les changements de tuteurs pour ce groupe de tutorat
        $changements = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:ChangementGroupeTuteur')
            ->findByGroupe($groupe);
        
        // On effectue les modifications
        $tuteurs = array();
        foreach ($changements as $changement) {
            if ($changement->getAction() == ChangementGroupeTuteur::CHANGEMENT_ACTION_AJOUT) {
                $tuteurs = array_merge($tuteurs, array($changement->getTuteur()));
            } elseif ($changement->getAction() == ChangementGroupeTuteur::CHANGEMENT_ACTION_SUPPRESSION) {
                $tuteurs = array_diff($tuteurs, array($changement->getTuteur()));
            }
        }
        
        return $tuteurs;
    }
    
    /**
     * Helper
     * Retourne la liste des lyceens actuels d'un groupe de tutorat
     *
     * @param Groupe $groupe: groupe de tutorat
     * @return array(Lyceen)
     */
    public function getLyceensForGroupe(Groupe $groupe)
    {
        // On considère tous les changements de tuteurs pour ce groupe de tutorat
        $changements = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:ChangementGroupeLyceen')
            ->findByGroupe($groupe);
        
        // On effectue les modifications
        $lyceens = array();
        foreach ($changements as $changement) {
            if ($changement->getAction() == ChangementGroupeLyceen::CHANGEMENT_ACTION_AJOUT) {
                $lyceens = array_merge($lyceens, array($changement->getLyceen()));
            } elseif ($changement->getAction() == ChangementGroupeLyceen::CHANGEMENT_ACTION_SUPPRESSION) {
                $lyceens = array_diff($lyceens, array($changement->getLyceen()));
            }
        }
        
        return $lyceens;
    }
    
    /**
     * Helper
     * Retourne la liste des enseignants pour un lycée.
     *
     * @param Lycee $lycee: lycée
     * @return array(Enseignant)
     */
    public function getEnseignantsForLycee(Lycee $lycee)
    {
        // On considère tous les changements d'enseignants pour ce lycée
        $changements = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:ChangementEnseignantLycee')
            ->findByLycee($lycee);
        
        // On effectue les modifications
        $enseignants = array();
        foreach ($changements as $changement) {
            if ($changement->getAction() == ChangementEnseignantLycee::CHANGEMENT_ACTION_AJOUT) {
                $enseignants = array_merge($enseignants, array($changement->getEnseignant()));
            } elseif ($changement->getAction() == ChangementEnseignantLycee::CHANGEMENT_ACTION_SUPPRESSION) {
                $enseignants = array_diff($enseignants, array($changement->getEnseignant()));
            }
        }
        
        // On trie les enseignants
        usort($enseignants, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        return $enseignants;
    }
    
    /**
     * Helper
     * Retourne la liste des roles associés à un enseignant pour un lycée
     *
     * @param Enseignant $enseignant
     * @param Lycee $lycee
     * @return array(string)
     */
    public function getRolesForEnseignantInLycee($enseignant, Lycee $lycee)
    {
        // On considère tous les changements d'enseignants pour ce lycée
        $changements = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:ChangementEnseignantLycee')
            ->findByEnseignant($enseignant);
            
        // On parcourt les modifications
        $roles = array();
        foreach ($changements as $changement) {
            if ($changement->getLycee() == $lycee) {
                if ($changement->getAction() == ChangementEnseignantLycee::CHANGEMENT_ACTION_AJOUT) {
                    $roles = array_merge($roles, array($changement->getRole()));
                } else {
                    $roles = array_diff($roles, array($changement->getRole()));
                }
            }
        }
        return $roles;
    }
    
    /**
     * Affiche la page d'un lycée.
     *
     * @param integer $lycee: id du lycée
     */
    public function voirAction($lycee)
    {
        $lycee = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:Lycee')
            ->find($lycee);
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycée !');
        
        // On trouve les enseignants et leurs rôles
        $enseignants = $this->getEnseignantsForLycee($lycee);
        $roles = array();
        foreach ($enseignants as $enseignant) {
            $roles[$enseignant->getId()] = $this->getRolesForEnseignantInLycee($enseignant, $lycee);
        }
        
        // On trouve les groupes de tutorat
        $groupes = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:Groupe')
            ->findByLyceeForCurrentYear($lycee);
            
        // On trouve les tuteurs, les lycéens et les séances à venir
        $tuteurs = array();
        $lyceens = array();
        $seances = array();
        foreach ($groupes as $groupe) {
            $tuteurs = array_merge($tuteurs, $this->getTuteursForGroupe($groupe));
            $lyceens = array_merge($lyceens, $this->getLyceensForGroupe($groupe));
            
            // On rassemble les séances à venir
            $query = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')
                ->createQueryBuilder('s')
                ->where('s.groupe = :groupe_id')
                ->setParameter('groupe_id', $groupe->getId())
                ->andWhere('s.fin > :maintenant')
                ->setParameter('maintenant', new \DateTime())
                ->getQuery();
            $seances = array_merge($seances, $query->getResult());
        }
        
        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        usort($lyceens, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        return $this->render('CECTutoratBundle:Lycees:voir.html.twig', array(
            'lycee'        => $lycee,
            'lyceens'      => $lyceens,
            'tuteurs'      => $tuteurs,
            'enseignants'  => $enseignants,
            'roles'        => $roles,
            'groupes'      => $groupes,
            'seances'      => $seances,
        ));
    }
    
    /**
     * Affiche un résumé des informations du lycée :
     * nom, adresse, cordée, statut, numéro de téléphone, source/pivot, ZEP ou non,
     * proviseur, référent, type de tutorat, niveaux concernés, nombre de lycéens et de tuteurs.
     *
     * @param Lycee $lycee: lycée
     */
    public function apercuAction(Lycee $lycee)
    {
        // On récupère les cordées pour le lycée
        $cordees = $this->getCordeesForLycee($lycee);
        $cordees = array_map(function ($lycee) {
            return $lycee->getNom();
        }, $cordees);
        
        // On récupère le proviseur et le référent
        $enseignants = $this->getEnseignantsForLycee($lycee);
        $proviseur = null;
        $referent = null;
        foreach ($enseignants as $enseignant) {
            $roles = $this->getRolesForEnseignantInLycee($enseignant, $lycee);
            if (in_array('Professeur référent', $roles)) $referent = $enseignant;
            if (in_array('Chef d\'établissement', $roles)) $proviseur = $enseignant;
        }
        
        // On récupère les groupes de tutorat
        $groupes = $this->getDoctrine()
            ->getRepository('CECTutoratBundle:Groupe')
            ->findByLyceeForCurrentYear($lycee);
            
        // On récupère les VP Lycées
        $VPs = $this->getVPsForLycee($lycee);
        $VPs = array_map(function ($VP) {
            return $VP->getPrenom() . ' ' . $VP->getNom();
        }, $VPs);
            
        // On trouve les tuteurs, les lycéens, les niveaux, les types de tutorat et les prochaines séances
        $tuteurs = array();
        $lyceens = array();
        $types = array();
        $niveaux = array();
        $seances = array();
        foreach ($groupes as $groupe) {
            $tuteurs = array_merge($tuteurs, $this->getTuteursForGroupe($groupe));
            $lyceens = array_merge($lyceens, $this->getLyceensForGroupe($groupe));
            $types = array_merge($types, array($groupe->getTypeDeTutorat()));
            $niveaux = array_merge($niveaux, array($groupe->getNiveau()));
        }
        
        return $this->render('CECTutoratBundle:Lycees:apercu.html.twig', array(
            'cordees'  => $cordees,
            'lycee'    => $lycee,
            'referent' => $referent,
            'proviseur'=> $proviseur,
            'tuteurs'  => $tuteurs,
            'lyceens'  => $lyceens,
            'niveaux'  => $niveaux,
            'types'    => $types,
            'VPs'      => $VPs,
        ));
    }
}
