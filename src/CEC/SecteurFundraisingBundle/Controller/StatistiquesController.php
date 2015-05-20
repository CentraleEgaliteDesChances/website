<?php

namespace CEC\SecteurFundraisingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

use CEC\TutoratBundle\Entity\GroupeTuteurs;
use CEC\TutoratBundle\Entity\GroupeEleves;
use CEC\TutoratBundle\Entity\Cordee;

class StatistiquesController extends Controller
{


    /**
    *
    * Affiche le menu de sélection de la page de statistiques
    *
    * @Template()
    */
    public function menuAction($request)
    {
        $groupeTuteurs = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeTuteurs')->findAll();

        $groupeLyceens = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeEleves')->findAll();

        $anneesScolaires = array();

        // On récupère toutes les années scolaires passées ou il y a eu une action de tutorat
        foreach($groupeTuteurs as $g)
        {
            $annee = $g->getAnneeScolaire();
            if(!in_array($annee, $anneesScolaires))
                $anneesScolaires[] = $annee;
        }

        foreach($groupeLyceens as $g)
        {
            $annee = $g->getAnneeScolaire();
            if(!in_array($annee, $anneesScolaires))
                $anneesScolaires[] = $annee;
        }

        usort($anneesScolaires, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? 1 : -1;
        });

        return array('anneesScolaires' => $anneesScolaires, 'request' => $request);
    }

    /**
    * 
    * Affiche les stats générales d'une année scolaire choisie (celle en cours par défaut)
    *
    * @param string $annees : annees de l'annee scolaire choisie
    *
    * @Template()
    */
    public function statsGeneralAction($annees)
    {

        // On récupère l'année sélectionnée ou celle en cours si aucune n'est choisie
        $anneeScolaire = $annees ? AnneeScolaire::withAnnees($annees) : AnneeScolaire::withDate();

        $doctrine = $this->getDoctrine();

        // On filtre les groupes de tutorat qui ont été actifs pour l'année donnée
        $groupes = $doctrine->getRepository('CECTutoratBundle:GroupeTuteurs')->findByAnneeScolaire($anneeScolaire);
        $groupes = array_map(function(GroupeTuteurs $g){ return $g->getGroupe();}, $groupes);

        // Partenariats
        $nbrCordees = $doctrine->getRepository('CECTutoratBundle:Cordee')->comptePourAnneeScolaire($anneeScolaire);
        $nbrLycees = $doctrine->getRepository('CECTutoratBundle:Lycee')->compte($anneeScolaire);
        $nbrLyceens = count($doctrine->getRepository('CECTutoratBundle:GroupeEleves')->findByAnneeScolaire($anneeScolaire));

        // Encadrement
        $nbrTuteurs = count($doctrine->getRepository('CECTutoratBundle:GroupeTuteurs')->findByAnneeScolaire($anneeScolaire));
        $tauxEncadrement = ($nbrTuteurs <> 0) ? number_format($nbrLyceens / $nbrTuteurs, 1) : '—';

        // Tutorat
        $nbrSeances = $doctrine->getRepository('CECTutoratBundle:Seance')->comptePourAnneeScolaire($anneeScolaire);
        $nbrHeuresTutorat = $doctrine->getRepository('CECTutoratBundle:Seance')
            ->compteHeuresTutoratPourAnneeScolaire($anneeScolaire);
        $heureLyceen = $nbrLyceens * $nbrHeuresTutorat;

        // Activites
        $nbrActivites = $doctrine->getRepository('CECActiviteBundle:Activite')->compte();
        $nbrActisUtilisees = $doctrine->getRepository('CECActiviteBundle:Activite')
            ->compteUtiliseesPourAnneeScolaire($anneeScolaire);
        $tauxUtilisationActis = ($nbrActivites <> 0) ? $nbrActisUtilisees / $nbrActivites : '—';

        // Sorties
        $nbrSorties = $doctrine->getRepository('CECSecteurSortiesBundle:Sortie')->comptePourAnneeScolaire($anneeScolaire);
        $nbrLyceensSortie = $doctrine->getRepository('CECSecteurSortiesBundle:Sortie')->compteLyceensSortiePourAnneeScolaire($anneeScolaire);

        return array(
            'anneeScolaire'          => $anneeScolaire,
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
            'nbr_sorties'             => $nbrSorties,
            'nbr_lyceens_sortie'     => $nbrLyceensSortie,
        );
    }

    /**
    * 
    * Affiche les stats détaillées d'une année scolaire choisie (celle en cours par défaut)
    *
    * @param string $annees : annees de l'annee scolaire choisie
    *
    * @Template()
    */
    public function statsDetailAction($annees)
    {
        // On récupère l'année sélectionnée ou celle en cours si aucune n'est choisie
        $anneeScolaire = $annees ? AnneeScolaire::withAnnees($annees) : AnneeScolaire::withDate();

        $doctrine = $this->getDoctrine();

        $groupes = $doctrine->getRepository('CECTutoratBundle:GroupeTuteurs')->findByAnneeScolaire($anneeScolaire);
        $groupes = array_unique(array_map(function(GroupeTuteurs $g){ return $g->getGroupe();}, $groupes));

        // Tableau qui contiendra une ligne par groupe avec les stats voulues pour le groupe
        $statsDetailTutorat = array();

        foreach($groupes as $g)
        {
            $minutesTutorat = 0;
            $seances = $g->getSeances();
            foreach ($seances as $seance)
            {
                if($anneeScolaire->contientDate($seance->getDate()))
                {
                    $duree = $seance->retreiveFin()->diff($seance->retreiveDebut());
                    $minutesTutorat += $duree->h * 60 + $duree->i;
                }
            }

            $heuresTutorat =floor($minutesTutorat / 60);

            $statsDetailTutorat[] = array($g, $heuresTutorat);
        }

        return array(
                        'anneeScolaire' => $anneeScolaire,
                        'statsDetailTutorat' => $statsDetailTutorat
                    );
    }

    /**
    * Affiche les données de suivi de l'évolution de l'effectif des tutorés et des tuteurs
    *
    * @Template()
    */
    public function effectifAction()
    {
        $doctrine = $this->getDoctrine();

        // Données de l'onglet 'Général'

        // On récupère l'ensemble des participations au tutorat pour chaque niveau
        $groupesSecondes = $doctrine->getRepository('CECTutoratBundle:Groupe')->findByNiveau('Secondes');
        $participationsSecondes = array();

        foreach($groupesSecondes as $g)
        {
            $participationsSecondes = array_merge($g->getLyceensParAnnee()->toArray(), $participationsSeconde);
        }

        $groupesPremieres = $doctrine->getRepository('CECTutoratBundle:Groupe')->findByNiveau('Premières');
        $participationsPremieres = array();

        foreach($groupesPremieres as $g)
        {
            $participationsPremieres = array_merge($g->getLyceensParAnnee()->toArray(), $participationsPremieres);
        }

        $groupesTerminales = $doctrine->getRepository('CECTutoratBundle:Groupe')->findByNiveau('Terminales');
        $participationsTerminales = array();

        foreach($groupesTerminales as $g)
        {
            $participationsTerminales = array_merge($g->getLyceensParAnnee()->toArray(), $participationsTerminales);
        }

        // On rassemble les années précédant l'année sélectionnée dans le menu et on les trie par ordre croissant.
        $annees = array();

        foreach($participationsSecondes as $p)
        {
            $annee = $p->getAnneeScolaire();
            $anneeActuelle = AnneeScolaire::withDate();
            if(!($annee->getAnneeInferieure()>$anneeActuelle->getAnneeInferieure()) and !in_array($annee, $annees))
            {
                $annees[] = $annee;
            }
        }

        foreach($participationsPremieres as $p)
        {
            $annee = $p->getAnneeScolaire();
            $anneeActuelle = AnneeScolaire::withDate();
            if(!($annee->getAnneeInferieure()>$anneeActuelle->getAnneeInferieure()) and !in_array($annee, $annees))
            {
                $annees[] = $annee;
            }
        }

        foreach($participationsTerminales as $p)
        {
            $annee = $p->getAnneeScolaire();
            $anneeActuelle = AnneeScolaire::withDate();
            if(!($annee->getAnneeInferieure()>$anneeActuelle->getAnneeInferieure()) and !in_array($annee, $annees))
            {
                $annees[] = $annee;
            }
        }

        usort($annees, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? -1 : 1;
        });

        // Tableau recensant les participations par année scolaire : { key anneeScolaire : value array(Secondes,Premières, Terminales)}
        $participationsParAnnee= array();

        foreach($annees as $a)
        {
            $partSecAnnee = array_filter($participationsSecondes, function(GroupeEleves $ge) use($a){ return ($ge->getAnneeScolaire() == $a);});
            $partPremAnnee = array_filter($participationsPremieres, function(GroupeEleves $ge) use($a){ return ($ge->getAnneeScolaire() == $a);});
            $partTermAnnee = array_filter($participationsTerminales, function(GroupeEleves $ge) use($a){ return ($ge->getAnneeScolaire() == $a);});

            $participationsParAnnee[$a->afficherAnnees()] = array($partSecAnnee, $partPremAnnee, $partTermAnnee);
        }


        return array('participationsParAnnee' => $participationsParAnnee);
    }

    /**
    * Affiche les données de suivi du suivi du programme de tutorat par les tutorés
    *
    * @Template()
    */
    public function engagementAction()
    {

    }

    /**
    * Affiche les données de participations aux sorties
    *
    * @Template()
    */
    public function sortiesAction()
    {

    }
}
