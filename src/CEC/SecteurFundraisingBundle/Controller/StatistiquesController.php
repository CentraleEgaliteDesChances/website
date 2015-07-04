<?php

namespace CEC\SecteurFundraisingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

use CEC\TutoratBundle\Entity\GroupeTuteurs;
use CEC\TutoratBundle\Entity\GroupeEleves;
use CEC\TutoratBundle\Entity\Cordee;
use CEC\TutoratBundle\Entity\Lycee;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\ActiviteBundle\Entity\CompteRendu;
use CEC\SecteurSortiesBundle\Entity\Sortie;
use CEC\SecteurSortiesBundle\Entity\SortieEleve;

class StatistiquesController extends Controller
{
    /**
    *
    * Fonction récupérant les années ayant connu une activité
    */
    public function anneesActives()
    {
        $anneesTuteurs = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeTuteurs')->findAll();
        $anneesTuteurs = array_map(function(GroupeTuteurs $g){return $g->getAnneeScolaire();}, $anneesTuteurs);

        $anneesLyceens = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeEleves')->findAll();
        $anneesLyceens = array_map(function(GroupeEleves $g){ return $g->getAnneeScolaire();}, $anneesLyceens);

        $anneesScolaires = array_unique(array_merge($anneesTuteurs, $anneesLyceens));

        $anneesScolaires = array_filter($anneesScolaires, function(AnneeScolaire $a){ return (AnneeScolaire::comparer(AnneeScolaire::withDate(), $a) >= 0);});

        return $anneesScolaires;
    }

    /**
    *
    * Affiche le menu de sélection de la page de statistiques
    *
    * @Template()
    */
    public function menuAction($request)
    {
        $anneesScolaires = $this->anneesActives();

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

    // Données de base 
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

    // On renvoie les données
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
    // Données de base
        $anneeScolaire = $annees ? AnneeScolaire::withAnnees($annees) : AnneeScolaire::withDate();

        $doctrine = $this->getDoctrine();

    // Tableau tutorat
        // On récupère l'année sélectionnée ou celle en cours si aucune n'est choisie

        $groupes = $doctrine->getRepository('CECTutoratBundle:GroupeTuteurs')->findByAnneeScolaire($anneeScolaire);
        $groupes = array_unique(array_map(function(GroupeTuteurs $g){ return $g->getGroupe();}, $groupes));

        // Tableau qui contiendra une ligne par groupe avec les stats voulues pour le groupe
        $statsDetailTutorat = array();
        $presences = 0;

        foreach($groupes as $g)
        {
            $presences = 0;
            $minutesTutorat = 0;
            $seances = $g->getSeances();
            foreach ($seances as $seance)
            {
                if($anneeScolaire->contientDate($seance->getDate()) and $seance->getDate()<(new \DateTime()))
                {
                    $duree = $seance->retreiveFin()->diff($seance->retreiveDebut());
                    $minutesTutorat += $duree->h * 60 + $duree->i;

                    $presences += count($seance->getLyceens());
                }


            }

            $heuresTutorat =floor($minutesTutorat / 60);

            $statsDetailTutorat[] = array($g, $heuresTutorat, $presences);
        }

    // Tableau Activités 

        $activites = $doctrine->getRepository('CECActiviteBundle:Activite')->utiliseesPourAnneeScolaire($anneeScolaire);

        // Tableau recensant les stats de chaque activité par ligne
        $statsDetailActi = array();

        foreach($activites as $a)
        {
            $cRs = $doctrine->getRepository('CECActiviteBundle:CompteRendu')->findByActivite($a);
            $cRs = array_filter($cRs, function(CompteRendu $cR) use($anneeScolaire) { return $anneeScolaire->contientDate($cR->getDateCreation());});

            $nbrUtil = count($cRs);
            $moyCon = 0;
            $moyInt = 0;
            $moyAtt = 0;

            foreach($cRs as $cR)
            {
                $moyCon += $cR->getNoteContenu();
                $moyInt += $cR->getnoteInteractivite();
                $moyAtt += $cR->getNoteAtteinteObjectifs();
            }

            $moyCon = number_format($moyCon/$nbrUtil, 1);
            $moyInt = number_format($moyInt/$nbrUtil, 1);
            $moyAtt = number_format($moyAtt/$nbrUtil, 1);

            $statsDetailActi[] = array($a, $nbrUtil, $moyCon, $moyInt, $moyAtt);
        }

        // On trie pour classer les actis par ordre décroissant d'utilisation
        usort($statsDetailActi, function($ligne1, $ligne2){
        if($ligne1[1] == $ligne2[1]) return 0;
        return ($ligne1[1] < $ligne2[1]) ? 1 : -1;
        });

    // Tableau Sorties

        // Tableau des stats de chaque sortie
        $statsDetailSorties = array();

        $sorties = $doctrine->getRepository('CECSecteurSortiesBundle:Sortie')->findAll();

        $sorties = array_filter($sorties, function(Sortie $s) use($anneeScolaire) { return ($anneeScolaire->contientDate($s->getDateSortie()) and $s->getOkCR());});

        foreach($sorties as $s)
        {
            $sortieLyceen = $s->getLyceens();

            $nbrPresents = 0;
            foreach($sortieLyceen as $l)
            {
                if($l->getPresence())
                    $nbrPresents++;
            }

            $statsDetailSorties[] = array($s, $nbrPresents, $s->getPrix());

        }

    // Tableau participations aux sorties

        //Tableau des stats de participation par niveau
        $statsDetailPart = array();

        $groupesSecondes = $doctrine->getRepository('CECTutoratBundle:Groupe')->findByNiveau('Secondes');
        $lyceensSecondes = array();

        // On récupère tous les lycéens des groupes sélectionnés
        foreach($groupesSecondes as $g)
        {
            $lyceens = $g->getLyceensParAnnee();
            $lyceens = array_filter($lyceens, function(GroupeEleves $ge) use($anneeScolaire) { return ($ge->getAnneeScolaire() == $anneeScolaire);});
            $lyceensSecondes = array_merge($lyceensSecondes, array_map(function(GroupeEleves $ge){ return $ge->getLyceen();}, $lyceens));
        }

        $groupesPremieres = $doctrine->getRepository('CECTutoratBundle:Groupe')->findByNiveau('Premières');
        $lyceensPremieres = array();

        // On récupère tous les lycéens des groupes sélectionnés
        foreach($groupesPremieres as $g)
        {
            $lyceens = $g->getLyceensParAnnee();
            $lyceens = array_filter($lyceens, function(GroupeEleves $ge) use($anneeScolaire) { return ($ge->getAnneeScolaire() == $anneeScolaire);});
            $lyceensPremieres = array_merge($lyceensPremieres, array_map(function(GroupeEleves $ge){ return $ge->getLyceen();}, $lyceens));
        }

        $groupesTerminales = $doctrine->getRepository('CECTutoratBundle:Groupe')->findByNiveau('Terminales');
        $lyceensTerminales = array();

        // On récupère tous les lycéens des groupes sélectionnés
        foreach($groupesTerminales as $g)
        {
            $lyceens = $g->getLyceensParAnnee();
            $lyceens = array_filter($lyceens, function(GroupeEleves $ge) use($anneeScolaire) { return ($ge->getAnneeScolaire() == $anneeScolaire);});
            $lyceensTerminales = array_merge($lyceensTerminales, array_map(function(GroupeEleves $ge){ return $ge->getLyceen();}, $lyceens));
        }

        // Traitement données des lycéens de Seconde

        // Nb de lycéens ayant effectué 0 ou X+ sorties
        $nb0 = 0;
        $nb1 = 0;
        $nb2 = 0;
        $nb3 = 0;
        $nb4 = 0;
        $nb5 = 0;

        foreach($lyceensSecondes as $l)
        {
            $sorties = $l->getSorties();

            $nbSorties = 0;
            foreach($sorties as $s)
            {
                $sortie = $s->getSortie();
                if($anneeScolaire->contientDate($sortie->getDateSortie()))
                    $nbSorties++;
            }

            // On ne met un break que dans case 0 et les cases suivants dans l'ordre décroissant comme ca si $nbSorties correspond à un cas,
            // les incrémentations suivantes sont quand meme exécutées et on met correctement à jour toutes les valeurs. break dans case 1 pour
            // pas toujours effectuer le default.
            switch($nbSorties)
            {
                case 0:
                    $nb0++;
                    break;
                case 5:
                    $nb5++;
                case 4:
                    $nb4++;
                case 3:
                    $nb3++;
                case 2:
                    $nb2++;
                case 1:
                    $nb1++;
                    break;
                default:
                    $nb5++;
                    $nb4++;
                    $nb3++;
                    $nb2++;
                    $nb1++;
                    break;
            }
        }

        $statsDetailPart[] = array('Secondes', $nb0, $nb1, $nb2, $nb3, $nb4, $nb5);

        // Traitement données des lycéens de Première

        // Nb de lycéens ayant effectué 0 ou X+ sorties
        $nb0 = 0;
        $nb1 = 0;
        $nb2 = 0;
        $nb3 = 0;
        $nb4 = 0;
        $nb5 = 0;

        foreach($lyceensPremieres as $l)
        {
            $sorties = $l->getSorties();
<<<<<<< HEAD
            $sorties = array_map(function(SortieEleve $se){return $se->getSortie();}, $sorties);
            $sorties = array_filter($sorties, function(Sortie $s) use($anneeScolaire) { return $anneeScolaire->contientDate($s->getDateSortie());});
            $nbSorties = count($sorties);

=======
            $nbSorties = 0;
            foreach($sorties as $s)
            {
                $sortie = $s->getSortie();
                if($anneeScolaire->contientDate($sortie->getDateSortie()))
                    $nbSorties++;
            }
>>>>>>> Extern-site
            // On ne met un break que dans case 0 et les cases suivants dans l'ordre décroissant comme ca si $nbSorties correspond à un cas,
            // les incrémentations suivantes sont quand meme exécutées et on met correctement à jour toutes les valeurs. break dans case 1 pour
            // pas toujours effectuer le default.
            switch($nbSorties)
            {
                case 0:
                    $nb0++;
                    break;
                case 5:
                    $nb5++;
                case 4:
                    $nb4++;
                case 3:
                    $nb3++;
                case 2:
                    $nb2++;
                case 1:
                    $nb1++;
                    break;
                default:
                    $nb5++;
                    $nb4++;
                    $nb3++;
                    $nb2++;
                    $nb1++;
                    break;
            }
        }

        $statsDetailPart[] = array('Premières', $nb0, $nb1, $nb2, $nb3, $nb4, $nb5);

        // Traitement données des lycéens de Terminale

        // Nb de lycéens ayant effectué 0 ou X+ sorties
        $nb0 = 0;
        $nb1 = 0;
        $nb2 = 0;
        $nb3 = 0;
        $nb4 = 0;
        $nb5 = 0;

        foreach($lyceensTerminales as $l)
        {
            $sorties = $l->getSorties();
<<<<<<< HEAD
            $sorties = array_map(function(SortieEleve $se){return $se->getSortie();}, $sorties);
            $sorties = array_filter($sorties, function(Sortie $s) use($anneeScolaire) { return $anneeScolaire->contientDate($s->getDateSortie());});
            $nbSorties = count($sorties);
=======
            $nbSorties = 0;
            foreach($sorties as $s)
            {
                $sortie = $s->getSortie();
                if($anneeScolaire->contientDate($sortie->getDateSortie()))
                    $nbSorties++;
            }
>>>>>>> Extern-site

            // On ne met un break que dans case 0 et les cases suivants dans l'ordre décroissant comme ca si $nbSorties correspond à un cas,
            // les incrémentations suivantes sont quand meme exécutées et on met correctement à jour toutes les valeurs. break dans case 1 pour
            // pas toujours effectuer le default.
            switch($nbSorties)
            {
                case 0:
                    $nb0++;
                    break;
                case 5:
                    $nb5++;
                case 4:
                    $nb4++;
                case 3:
                    $nb3++;
                case 2:
                    $nb2++;
                case 1:
                    $nb1++;
                    break;
                default:
                    $nb5++;
                    $nb4++;
                    $nb3++;
                    $nb2++;
                    $nb1++;
                    break;
            }
        }

        $statsDetailPart[] = array('Terminales', $nb0, $nb1, $nb2, $nb3, $nb4, $nb5);
        
    // On renvoie les données
        return array(
                        'anneeScolaire' => $anneeScolaire,
                        'statsDetailTutorat' => $statsDetailTutorat,
                        'statsDetailActi' => $statsDetailActi,
                        'statsDetailSorties' => $statsDetailSorties,
                        'statsDetailPart' => $statsDetailPart,
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
            $participationsSecondes = array_merge($g->getLyceensParAnnee(), $participationsSecondes);
        }

        $groupesPremieres = $doctrine->getRepository('CECTutoratBundle:Groupe')->findByNiveau('Premières');
        $participationsPremieres = array();

        foreach($groupesPremieres as $g)
        {
            $participationsPremieres = array_merge($g->getLyceensParAnnee(), $participationsPremieres);
        }

        $groupesTerminales = $doctrine->getRepository('CECTutoratBundle:Groupe')->findByNiveau('Terminales');
        $participationsTerminales = array();

        foreach($groupesTerminales as $g)
        {
            $participationsTerminales = array_merge($g->getLyceensParAnnee(), $participationsTerminales);
        }

        // On rassemble les années précédant l'année sélectionnée dans le menu et on les trie par ordre croissant.
        $annees = $this->anneesActives();

        usort($annees, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? -1 : 1;
        });

        // Tableau recensant les participations par année scolaire : { key anneeScolaire : value array(Secondes,Premières, Terminales)}
        $statsEffectifGeneral= array();

        foreach($annees as $a)
        {
            $partSecAnnee = array_filter($participationsSecondes, function(GroupeEleves $ge) use($a){ return ($ge->getAnneeScolaire() == $a);});
            $partPremAnnee = array_filter($participationsPremieres, function(GroupeEleves $ge) use($a){ return ($ge->getAnneeScolaire() == $a);});
            $partTermAnnee = array_filter($participationsTerminales, function(GroupeEleves $ge) use($a){ return ($ge->getAnneeScolaire() == $a);});

            $effSecAnnee = count($partSecAnnee);
            $effPreAnnee = count($partPremAnnee);
            $effTerAnnee = count($partTermAnnee);

            $statsEffectifGeneral[$a->afficherAnnees()] = array($effSecAnnee, $effPreAnnee, $effTerAnnee, $effSecAnnee+$effPreAnnee+$effTerAnnee);
        }
    // Données de l'onglet 'Détail'

        // Données du tableau par Cordée et par lycée
        $cordees = $doctrine->getRepository('CECTutoratBundle:Cordee')->findAll();
        $cordees = array_filter($cordees, function(Cordee $c){return $c->isActive();});

        $anneesScolaires = array_reverse($this->anneesActives());

        // Tableaux des stats d'effectif par cordée et par lycée pour les différents niveaux
        $statsEffectifDetailSecondes = array();
        $statsEffectifsDetailPremieres = array();
        $statsEffectifDetailTerminales = array();
        $statsEffectifDetail = array();

        foreach($cordees as $c)
        {
            // Stats des effectifs pour la cordée par lycée
            $statsEffCordeeSecondes = array();
            $statsEffCordeePremieres = array();
            $statsEffCordeeTerminales = array();
            $statsEffCordee = array();

            $lycees = $c->getLycees();

            // On ne garde que les lycées sources
            $lycees = array_filter($lycees, function(Lycee $l){ return !($l->getPivot());});

            // On récupère tous les groupes liés à ces lycées puis on les trie en sous tableaux par niveau
            foreach($lycees as $l)
                {
                    $lyceens = $l->getLyceens();

                    // Effectif du lycée par année
                    $effLyceeAnnees = array();
                    $effLyceeSecondesAnnees = array();
                    $effLyceePremieresAnnees = array();
                    $effLyceeTerminalesAnnees = array();

                    foreach($anneesScolaires as $a)
                    {
                        // Effectif du lycée pour l'année $a
                        $effLycee = 0;
                        $effLyceeSecondes = 0;
                        $effLyceePremieres = 0;
                        $effLyceeTerminales = 0;

                        foreach($lyceens as $ly)
                        {
                            $groupeLyceen = $doctrine->getRepository('CECTutoratBundle:GroupeEleves')->findOneBy(array('anneeScolaire' => $a, 'lyceen'=>$ly));
                            if($groupeLyceen)
                            {
                                $groupe = $groupeLyceen->getGroupe();
                                $niveau = $groupe->getNiveau();

                                switch($niveau)
                                {
                                    case "Secondes":
                                        $effLyceeSecondes++;
                                        break;
                                    case "Premières":
                                        $effLyceePremieres++;
                                        break;
                                    case "Terminales":
                                        $effLyceeTerminales++;
                                        break;
                                    default:
                                        break;
                                }

                                $effLycee++;
                            }
                        }

                        // On rajoute les effectifs pour l'année étudiée
                        /* @var $a \CEC\MainBundle\AnneeScolaire\AnneeScolaire */
                        $effLyceeAnnees[$a->afficherAnnees()] = $effLycee;
                        $effLyceeSecondesAnnees[$a->afficherAnnees()] = $effLyceeSecondes;
                        $effLyceePremieresAnnees[$a->afficherAnnees()] = $effLyceePremieres;
                        $effLyceeTerminalesAnnees[$a->afficherAnnees()] = $effLyceeTerminales;
                    }

                    // On remplit le tableau de chaque cordée avec les infos de chaque lycée 
                    $arrayTemp = array($l);
                    $arrayTemp[] = $effLyceeAnnees;
                    $statsEffCordee[] = $arrayTemp;

                    $arrayTemp = array($l);
                    $arrayTemp[] = $effLyceeSecondesAnnees;
                    $statsEffCordeeSecondes[] = $arrayTemp;

                    $arrayTemp = array($l);
                    $arrayTemp[] = $effLyceePremieresAnnees;
                    $statsEffCordeePremieres[] = $arrayTemp;

                    $arrayTemp = array($l);
                    $arrayTemp[] = $effLyceeTerminalesAnnees;
                    $statsEffCordeeTerminales[] = $arrayTemp;

                }

                // On calcule le tableau des effectifs par année pour la cordéé générale
                $effectifsCordee = array();
                $effectifsCordeeSecondes = array();
                $effectifsCordeePremieres = array();
                $effectifsCordeeTerminales = array();

                $effLycee = array_map(function($a) { return $a[1];}, $statsEffCordee);
                $effLyceeSecondes = array_map(function($a) { return $a[1];}, $statsEffCordeeSecondes);
                $effLyceePremieres = array_map(function($a) { return $a[1];}, $statsEffCordeePremieres);
                $effLyceeTerminales = array_map(function($a) { return $a[1];}, $statsEffCordeeTerminales);
                
                foreach($effLycee as $temp)
                {
                    foreach($temp as $a => $eff)
                    {
                        if(array_key_exists($a, $effectifsCordee))
                        {
                            $effectifsCordee[$a] += $eff;
                        }
                        else
                        {
                            $effectifsCordee[$a] = $eff;
                        }
                    }
                }

                foreach($effLyceeSecondes as $temp)
                {
                    foreach($temp as $a => $eff)
                    {
                        if(array_key_exists($a, $effectifsCordeeSecondes))
                        {
                            $effectifsCordeeSecondes[$a] += $eff;
                        }
                        else
                        {
                            $effectifsCordeeSecondes[$a] = $eff;
                        }
                    }
                }

                foreach($effLyceePremieres as $temp)
                {
                    foreach($temp as $a => $eff)
                    {
                        if(array_key_exists($a, $effectifsCordeePremieres))
                        {
                            $effectifsCordeePremieres[$a] += $eff;
                        }
                        else
                        {
                            $effectifsCordeePremieres[$a] = $eff;
                        }
                    }
                }

                foreach($effLyceeTerminales as $temp)
                {
                    foreach($temp as $a => $eff)
                    {
                        if(array_key_exists($a, $effectifsCordeeTerminales))
                        {
                            $effectifsCordeeTerminales[$a] += $eff;
                        }
                        else
                        {
                            $effectifsCordeeTerminales[$a] = $eff;
                        }
                    }
                }

                // On remplit le tableau général avec les infos de chaque cordée

                $arrayTemp = array($c);
                $arrayTemp[] = $statsEffCordee;
                $arrayTemp[] = $effectifsCordee;
                $statsEffectifDetail[] = $arrayTemp;

                $arrayTemp = array($c);
                $arrayTemp[] = $statsEffCordeeSecondes;
                $arrayTemp[] = $effectifsCordeeSecondes;
                $statsEffectifDetailSecondes[] = $arrayTemp;

                $arrayTemp = array($c);
                $arrayTemp[] = $statsEffCordeePremieres;
                $arrayTemp[] = $effectifsCordeePremieres;
                $statsEffectifsDetailPremieres[] = $arrayTemp;

                $arrayTemp = array($c);
                $arrayTemp[] = $statsEffCordeeTerminales;
                $arrayTemp[] = $effectifsCordeeTerminales;
                $statsEffectifDetailTerminales[] = $arrayTemp;
        }

    // On renvoie les données
        return array('anneesScolaires' => $anneesScolaires,
                     'statsEffectifGeneral' => $statsEffectifGeneral,
                     'statsEffectifDetail' => $statsEffectifDetail,
                     'statsEffectifDetailSecondes' => $statsEffectifDetailSecondes,
                     'statsEffectifDetailPremieres' => $statsEffectifsDetailPremieres,
                     'statsEffectifDetailTerminales' => $statsEffectifDetailTerminales
                     );
    }

    /**
    * Affiche les données de suivi du suivi du programme de tutorat par les tutorés
    *
    * @Template()
    */
    public function engagementAction()
    {
        $doctrine = $this->getDoctrine();
        $annees = $this->anneesActives();

        usort($annees, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? -1 : 1;
        });


    // Données de l'onglet Général

        // Tableau qui contient les stats de suivi de ceux qui sont la cette année et qui étaient la l'année dernière
            $statsEngagementGeneral1 = array();

            foreach($annees as $a)
            {
                $anneePrecedente = new AnneeScolaire($a->getAnneeInferieure()-1);

                $donneesPrec = $doctrine->getRepository('CECTutoratBundle:GroupeEleves')->findByAnneeScolaire($anneePrecedente);
                $donneesAct = $doctrine->getRepository('CECTutoratBundle:GroupeEleves')->findByAnneeScolaire($a);

                $lyceensPrec = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $donneesPrec);

                //On récupère les lycéens par niveau
                $lyceensPrecSec = array_filter($donneesPrec, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Secondes");});
                $lyceensPrecSec = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensPrecSec);


                $lyceensPrecPrem = array_filter($donneesPrec, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Premières");});
                $lyceensPrecPrem = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensPrecPrem);

                $lyceensActPrem = array_filter($donneesAct, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Premières");});
                $lyceensActPrem = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensActPrem);

                $lyceensPrecTerm = array_filter($donneesPrec, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Terminales");});
                $lyceensPrecTerm = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensPrecTerm);

                $lyceensActTerm = array_filter($donneesAct, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Terminales");});
                $lyceensActTerm = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensActTerm);


                // Variables pour compter ceux qui étaient l'année précédente qui pouvaient rester et qui sont restés cette année
                $effPrec = count($lyceensPrec)-count($lyceensPrecTerm);
                $effAct = 0;

                $effPrecSec = count($lyceensPrecSec);

                $effPrecPrem = count($lyceensPrecPrem);
                $effActPrem = 0;

                $effActTerm = 0;

                foreach($lyceensActPrem as $l)
                {
                    if(in_array($l, $lyceensPrecSec))
                    {
                        $effActPrem++;
                        $effAct++;
                    }
                }

                foreach($lyceensActTerm as $l)
                {
                    if(in_array($l, $lyceensPrecPrem))
                    {
                        $effActTerm++;
                        $effAct++;
                    }
                }

                $statsEngagementGeneral1[$a->afficherAnnees()] = array($effPrec, $effAct, $effPrecSec, $effActPrem, $effPrecPrem, $effActTerm);
            }

        // On regarde maintenant les nouveaux tutorés qui auraient pu être à CEC l'année dernière
            $statsEngagementGeneral2 = array();

            foreach($annees as $a)
            {
                $anneePrecedente = new AnneeScolaire($a->getAnneeInferieure()-1);

                $donneesPrec = $doctrine->getRepository('CECTutoratBundle:GroupeEleves')->findByAnneeScolaire($anneePrecedente);
                $donneesAct = $doctrine->getRepository('CECTutoratBundle:GroupeEleves')->findByAnneeScolaire($a);

                // Il faut faire le tri pour différencier les nouveaux premières & terminales de lycées sans groupe de secondes de ceux qui auraient
                // effectivement pu etre à CEC l'année dernière
                $groupes = $doctrine->getRepository('CECTutoratBundle:Groupe')->findAll();
                $groupesInteressants = array();
                $lyceesSources = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->findByPivot (false);

                foreach($lyceesSources as $lycee)
                {
                    // On trie par niveau et on met tous les groupes sauf celui de plus bas niveau
                    $groupesDuLycee = $lycee->getGroupes();
                    $niveaux = array();
                    foreach($groupesDuLycee as $g)
                    {
                        switch($g->getNiveau())
                        {
                            case "Secondes":
                                $niveaux['Secondes'] = $g;
                                break;
                            case "Premières":
                                $niveaux['Premières'] = $g;
                                break;
                            case "Terminales":
                                $niveaux['Terminales'] = $g;
                                break;
                            default:
                                break;
                        }
                    }

                    switch(array_keys($niveaux))
                    {
                        case array('Secondes', 'Premières', 'Terminales'):
                            $groupesInteressants[] = $niveaux['Premières'];
                            $groupesInteressants[] = $niveaux['Terminales'];
                            break;
                        case array('Premières, Terminales'):
                            $groupesInteressants[] = $niveaux['Terminales'];
                            break;
                        default:
                            break;
                    }
                }

                $lyceensAct = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $donneesAct);
                $effTotal = count($lyceensAct);

                //On récupère les lycéens par niveau
                $lyceensPrecSec = array_filter($donneesPrec, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Secondes");});
                $lyceensPrecSec = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensPrecSec);


                $lyceensPrecPrem = array_filter($donneesPrec, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Premières");});
                $lyceensPrecPrem = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensPrecPrem);

                $lyceensActPrem = array_filter($donneesAct, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Premières");});
                $effTotalPrem = count($lyceensActPrem);
                // On ne garde maintenant que les lycéens des groupes intéressants
                $lyceensActPrem = array_filter($lyceensActPrem, function(GroupeEleves $g) use($groupesInteressants){return in_array($g->getGroupe(), $groupesInteressants);});
                $lyceensActPrem = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensActPrem);

                $lyceensPrecTerm = array_filter($donneesPrec, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Terminales");});
                $lyceensPrecTerm = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensPrecTerm);

                $lyceensActTerm = array_filter($donneesAct, function(GroupeEleves $g){return ($g->getGroupe()->getNiveau() == "Terminales");});
                $effTotalTerm = count($lyceensActTerm);
                // On ne garde maintenant que les lycéens des groupes intéressants.
                $lyceensActTerm = array_filter($lyceensActTerm, function(GroupeEleves $g)use($groupesInteressants){return in_array($g->getGroupe(), $groupesInteressants);});
                $lyceensActTerm = array_map(function(GroupeEleves $g){ return $g->getLyceen();}, $lyceensActTerm);


                // Variables pour compter ceux qui viennent d'arriver et qui auraient pu être la l'année dernière
                $effArrives = 0 ; 

                $effArrivesPrem = 0; 

                $effArrivesTerm = 0; 

                foreach($lyceensActPrem as $l)
                {
                    if(!(in_array($l, $lyceensPrecSec)))
                    {
                        $effArrivesPrem++;
                        $effArrives++;
                    }
                }

                foreach($lyceensActTerm as $l)
                {
                    if(!(in_array($l, $lyceensPrecPrem)))
                    {
                        $effArrives++;
                        $effArrives++;
                    }
                }

                $statsEngagementGeneral2[$a->afficherAnnees()] = array($effTotal, $effArrives, $effTotalPrem, $effArrivesPrem, $effTotalTerm, $effArrivesTerm);
            }

        // On regarde quels lycéens parmi ceux qui le pouvaient ont fait deux années de suite à CEC

    // On retourne les données
        // On retire la première année vu qu'il y a pas d'années précédentes pour faire les stats
        $annees = array_pop($annees);

        if(count($annees) == 1)
        {
            $annees = array($annees);
        }

        $statsEngagementGeneral3 = array();

        return array('anneesScolaires' => $annees,
                     'statsEngagementGeneral1' => $statsEngagementGeneral1,
                     'statsEngagementGeneral2' => $statsEngagementGeneral2,
                     'statsEngagementGeneral3' => $statsEngagementGeneral3,
                     );

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
