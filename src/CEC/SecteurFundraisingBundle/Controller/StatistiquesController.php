<?php

namespace CEC\SecteurFundraisingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\MainBundle\Utility\AnneeScolaire;

class StatistiquesController extends Controller
{
    /**
     * Affiche les statistiques de l'association.
     * Les statistiques permettent un retour détaillé pour les partenaires. Elles
     * concernent les partenariats et l'encadrement et l'activité de tutorat.
     *
     * @param string $annees Année scolaire sous la forme "2012-2013" à laquelle se rapporte les statistiques.
     * @Route(
     *     "/statistiques/{annees}",
     *     requirements = {"annees" = "^[[:digit:]]{4}\-[[:digit:]]{4}$"},
     *     defaults = {"annees" = null}
     * )
     * @Template()
     */
    public function voirAction($annees = null)
    {
        if ($annees) {
            $anneeScolaire = AnneeScolaire::withAnnees($annees);
        } else {
            $anneeScolaire = new AnneeScolaire(date('Y'));
        }
        $doctrine = $this->getDoctrine();
        
        // Partenariats
        $nbrCordees = $doctrine->getRepository('CECTutoratBundle:Cordee')->comptePourAnneeScolaire($anneeScolaire);
        $nbrLycees = $doctrine->getRepository('CECTutoratBundle:Lycee')->compte();
        $nbrLyceens = $doctrine->getRepository('CECTutoratBundle:Lyceen')->comptePourAnneeScolaire($anneeScolaire);
        
        // Encadrement
        $nbrTuteurs = $doctrine->getRepository('CECMembreBundle:Membre')->comptePourAnneeScolaire($anneeScolaire);
        $tauxEncadrement = ($nbrTuteurs <> 0) ? number_format($nbrLyceens / $nbrTuteurs, 1) : '—';
        
        // Tutorat
        $nbrSeances = $doctrine->getRepository('CECTutoratBundle:Seance')->comptePourAnneeScolaire($anneeScolaire);
        $nbrHeuresTutorat = $doctrine->getRepository('CECTutoratBundle:Seance')
            ->compteHeuresTutoratPourAnneeScolaire($anneeScolaire);
        $heureLyceen = $nbrSeances * $nbrHeuresTutorat;
        
        // Activites
        $nbrActivites = $doctrine->getRepository('CECActiviteBundle:Activite')->compte();
        $nbrActisUtilisees = $doctrine->getRepository('CECActiviteBundle:Activite')
            ->compteUtiliseesPourAnneeScolaire($anneeScolaire);
        $tauxUtilisationActis = ($nbrActivites <> 0) ? $nbrActisUtilisees / $nbrActivites : '—';
        
        return array(
            'annee_scolaire'          => $anneeScolaire,
            'nbr_cordees'             => $nbrCordees,
            'nbr_lycees'              => $nbrLycees,
            'nbr_lyceens'             => $nbrLyceens,
            'nbr_tuteurs'             => $nbrTuteurs,
            'taux_encadrement'        => $tauxEncadrement,
            'nbr_seances'             => $nbrSeances,
            'nbr_heures_tutorat'      => $nbrHeuresTutorat,
            'heure_lyceen'            => $heureLyceen,
            'nbr_activites'           => $nbrActivites,
            'nbr_actis_utilisees'     => $nbrActisUtilisees,
            'taux_utilisation_actis'  => $tauxUtilisationActis,
        );
    }
}
