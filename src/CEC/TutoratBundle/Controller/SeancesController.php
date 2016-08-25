<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\TutoratBundle\Entity\Seance;
use CEC\TutoratBundle\Form\Type\SeanceType;
use CEC\ActiviteBundle\Form\Type\CompteRenduType;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

use CEC\TutoratBundle\Entity\GroupeEleves;
use CEC\TutoratBundle\Entity\GroupeTuteurs;

use CEC\MembreBundle\Entity\Membre;
use CEC\MembreBundle\Entity\Eleve;

use \DateTime;

class SeancesController extends Controller
{

    /**
     * Affiche la page de la séance de tutorat.
     * Il s'agit aussi de la page qui s'affiche sur le tableau de bord lorsqu'une
     * prochaine séance est disponible.
     *
     * @param integer $seance: id de la séance de tutorat
     * @return array
     */
    public function voirAction($seance)
    {
        $seance = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->find($seance);
        if (!$seance) throw $this->createNotFoundException('Impossible de trouver la séance de tutorat !');
        
        // On détermine si la séance est à venir ou non
        $seanceAVenir = $seance->getGroupe()
            && $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->findOneAVenir($seance->getGroupe()) == $seance;

        $anneeScolaire = AnneeScolaire::withDate($seance->getDate());
        
        // Rassemble les lycéens et les tuteurs du groupe de tutorat
        if ($seance->getGroupe())
        {
            $lyceens = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeEleves')->findBy(array('groupe' => $seance->getGroupe(),'anneeScolaire' => $anneeScolaire));
            $lyceens = array_map(function(GroupeEleves $e){ return $e->getLyceen();}, $lyceens);
            $tuteurs = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeTuteurs')->findBy(array('groupe' => $seance->getGroupe(),'anneeScolaire' => $anneeScolaire));
            $tuteurs = array_map(function(GroupeTuteurs $t){ return $t->getTuteur();}, $tuteurs);
        } else {
            $lyceens = array();
            $tuteurs = array();
        }
        
        // On génère les formulaires de compte-rendu
        $crForms = array();
        if($this->getUser() instanceof \CEC\MembreBundle\Entity\Membre)
        {
            foreach ($seance->getCompteRendus() as $compteRendu) {
                $compteRendu->setAuteur($this->getUser());
                $crForms[$compteRendu->getId()] = $this->createForm(new CompteRenduType(), $compteRendu);
            }
        }
                
        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function(Membre $a, Membre $b) { return strcmp($a->getNom(), $b->getNom()); });
        usort($lyceens, function(Eleve $a, Eleve $b) { return strcmp($a->getNom(), $b->getNom()); });
        
        // On remplace les placeholders par défaut de SéanceType par les données du groupe
        $options = array();
        if($seance->getGroupe())
        {
            $options['lieu'] = $seance->getGroupe()->getLieu();
            $options['rendezVous'] = $seance->getGroupe()->getRendezVous();
            $options['debut'] = $seance->getGroupe()->getDebut()->format('H:i');
            $options['fin'] = $seance->getGroupe()->getFin()->format('H:i');
        }
        // On génère le formulaire d'édition de la séance
        $form = $this->createForm(new SeanceType(), $seance, $options);
        
        // Par défaut, on masque le modal
        $afficherModal = false;
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST' && $request->request->has('editer_seance'))
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->getFlashBag()->add('success', 'Les informations de la séance ont bien été modifiées.');
                return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
            } else {
                $afficherModal = true;
            }
        }
        if ($request->getMethod() == 'POST' && $request->request->has('editer_cr'))
        {
            $compteRenduId = $request->request->get('cr_id');
            $compteRendu = $this->getDoctrine()->getRepository('CECActiviteBundle:CompteRendu')->findOneBy(array(
                'id' => $compteRenduId,
                'seance' => $seance->getId(),
            ));
            if (!$compteRendu) throw $this->createNotFoundException('Impossible de trouver le compte-rendu a éditer !');
            
            $crForm = $crForms[$compteRenduId];
            $crForm->handleRequest($request);
            if ($crForm->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->getFlashBag()->add('success', 'Le compte-rendu de séance portant sur l\'activité "' . $compteRendu->getActivite()->getTitre() . '" a bien été envoyé.');
                return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Une erreur s\'est glissée dans le compte-rendu ; merci de vous y reporter pour plus d\'informations.');
            }
        }
        
        // On génère les vues de formulaires pour les CR
        $crFormViews = array();
        foreach ($crForms as $compteRenduId => $form) $crFormViews[$compteRenduId] = $form->createView();
        
        // On charge les informations de séance à afficher
        $infos = array();
        
        return $this->render('CECTutoratBundle:Seances:voir.html.twig', array(
            'seance'         => $seance,
            'lyceens'        => $lyceens,
            'tuteurs'        => $tuteurs,
            'form'           => $form->createView(),
            'afficher_modal' => $afficherModal,
            'seance_a_venir' => $seanceAVenir,
            'cr_forms'       => $crFormViews,
            'infos'          => $infos,
        ));
    }

    /**
     * Crée une séance de tutorat
     *
     * @param integer $groupe id du groupe auquel la séance est reliée
     * @Template()
     */
    public function creerAction($groupe)
    {

        $groupe = $this->getDoctrine()->getRepository("CECTutoratBundle:Groupe")->find($groupe);
        if(!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');

        // On génère le formulaire de nouvelle séance

        // Tableau pour les placeholders (on met les infos du groupe)
        $options = array(
            'lieu' => $groupe->getLieu(),
            'rendezVous' => $groupe->getRendezVous(),
            'debut' => $groupe->getDebut()->format('H:i'),
            'fin' => $groupe->getFin()->format('H:i'));

        $nouvelleSeance = new Seance();
        $nouvelleSeanceForm = $this->createForm(new SeanceType(), $nouvelleSeance, $options);
        $nouvelleSeance->setGroupe($groupe);

        $anneeScolaire = AnneeScolaire::withDate();

        $tuteurs = $groupe->getTuteursAnnee($anneeScolaire);

        foreach ($tuteurs as $tuteur) {
            $tuteur->addSeance($nouvelleSeance);
            $nouvelleSeance->addTuteur($tuteur);
        }

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST' )
        {
            $nouvelleSeanceForm->handleRequest($request);
            if ($nouvelleSeanceForm->isValid())
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($nouvelleSeance);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', 'La séance de tutorat a bien été ajoutée.');
                return $this->redirect($this->generateUrl('groupe', array('groupe' => $groupe->getId())));
            }
        }

        return [
            'nouvelleSeanceForm' => $nouvelleSeanceForm->createView(),
            'groupe' => $groupe,
            'anneeScolaire' => AnneeScolaire::withDate()
        ];
    }
    
    /**
     * Supprime la séance de tutorat.
     *
     * @param integer $seance: id de la séance de tutorat
     */
    public function supprimerAction($seance)
    {
        $seance = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->find($seance);
        if (!$seance) throw $this->createNotFoundException('Impossible de trouver la séance de tutorat !');
        
        // Retire le groupe en le gardant en mémoire
        $groupe = $seance->getGroupe();
        $seance->setGroupe(null);
        
        // Retire les tuteurs et les lycéens
        foreach ($seance->getTuteurs() as $tuteur) $seance->removeTuteur($tuteur);
        foreach ($seance->getLyceens() as $lyceen) $seance->removeLyceen($lyceen);
        
        // Supprime la séance
        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->remove($seance);
        $entityManager->flush();
        
        $this->get('session')->getFlashBag()->add('success', 'La séance de tutorat a bien été supprimée.');
        return $this->redirect($this->generateUrl('groupe', array('groupe' => $groupe->getId())));
    }
    
    /**
     * Bascule l'état d'un tuteur pour cette séance :
     * s'il est marqué comme participant, on le retire et vice-versa.
     *
     * @param integer $seance: id de la séance de tutorat
     * @param integer $tuteur: id du tuteur dont l'état doit être basculé
     */
    public function basculerTuteurAction($seance, $tuteur)
    {
        $seance = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->find($seance);
        if (!$seance) throw $this->createNotFoundException('Impossible de trouver la séance de tutorat !');
        
        $tuteur = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($tuteur);
        if (!$tuteur) throw $this->createNotFoundException('Impossible de trouver le tuteur !');
        
        // On bascule l'état
        if (in_array($tuteur, $seance->getTuteurs()))
        {
            $seance->removeTuteur($tuteur);
        } else {
            $seance->addTuteur($tuteur);
        }
		
        
        $this->getDoctrine()->getEntityManager()->flush();
        return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
    }

    /**
     * Bascule l'état d'un tuteur pour cette séance :
     * s'il est marqué comme participant, on le retire et vice-versa.
     *
     * @param integer $seance: id de la séance de tutorat
     * @param integer $lyceen: id du lycéen dont l'état doit être basculé
     */
    public function basculerEleveAction($seance, $lyceen)
    {
        $seance = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->find($seance);
        if (!$seance) throw $this->createNotFoundException('Impossible de trouver la séance de tutorat !');
        
        $lyceen = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lyceen !');
        
        // On bascule l'état
        if (in_array($lyceen, $seance->getLyceens()))
        {
            $seance->removeLyceen($lyceen);
        } else {
            $seance->addLyceen($lyceen);
        }
        
        
        $this->getDoctrine()->getEntityManager()->flush();
        return $this->redirect($this->generateUrl('seance', array('seance' => $seance->getId())));
    }
    

    /**
    *
    * Affiche la liste des présences/absences d'un élève par année scolaire
    * @param integer $lyceen : id du lycéen
    *
    * @Template()
    */
    public function absencesAction($lyceen)
    {
        $lyceen = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lyceen !');

        $data = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeEleves')->findByLyceen($lyceen);
        if(!$data) throw $this->createNotFoundException('Pas d\'activité de tutorat');

        $anneesScolaires = array();
        $seancesTotal = array();

        foreach($data as $groupeLyceen)
        {
            $date =  $groupeLyceen->getAnneeScolaire();
            $groupe = $groupeLyceen->getGroupe();

            if(!in_array($date, $anneesScolaires))
            {
                $anneesScolaires[] = $date;
            }

            if(!array_key_exists($date->afficherAnnees(), $seancesTotal))
            {
                $seancesTotal[$date->afficherAnnees()]= $groupe->getSeances();
                $seancesTotal[$date->afficherAnnees()] = array_filter($seancesTotal[$date->afficherAnnees()], function(Seance $s) use($date)
                    {
                        return $date->contientDate($s->getDate());
                    }
                );
            }

        }

        usort($anneesScolaires, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? 1 : -1;
        });

        return array(
                     'eleve' => $lyceen,
                     'seancesTotal' => $seancesTotal,
                     'anneesScolaires' => $anneesScolaires);


    }

    /**
    *
    * Affiche les absences des élèves d'un lycée
    *
    * @param integer $lycee : id du lycée cherché
    *
    * @Template()
    */
    public function absencesLyceeAction($lycee)
    {
        $lycee = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->find($lycee);
        if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycee !');

        $groupes = $lycee->getGroupes();
        $anneesScolaires = array();

        foreach($groupes as $groupe)
        {
            $groupeLyceens = $groupe->getLyceensParAnnee();
            foreach($groupeLyceens as $groupeLyceen)
            {
                $lyceen = $groupeLyceen->getLyceen();
                if($lyceen->getLycee() == $lycee)
                {
                    if(!in_array($groupeLyceen->getAnneeScolaire(), $anneesScolaires))
                        $anneesScolaires[] = $groupeLyceen->getAnneeScolaire();
                }
            }
        }

        usort($anneesScolaires, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? 1 : -1;
        });

        return array('anneesScolaires' => $anneesScolaires, 'lycee'=>$lycee);

    }

}
