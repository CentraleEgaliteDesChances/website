<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\TutoratBundle\Entity\Seance;
use CEC\MembreBundle\Entity\Eleve;
use CEC\MembreBundle\Entity\Membre;
use CEC\TutoratBundle\Entity\GroupeTuteurs;
use CEC\TutoratBundle\Entity\GroupeEleves;

use CEC\TutoratBundle\Form\Type\GroupeType;
use CEC\TutoratBundle\Form\Type\AjouterLyceenType;
use CEC\TutoratBundle\Form\Type\AjouterTuteurType;
use CEC\TutoratBundle\Form\Type\SeanceType;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;


class GroupesController extends Controller
{
    /**
     * Affiche la liste des groupes de tutorat
     */
    public function tousAction()
    {
        $anneeScolaire = AnneeScolaire::withDate();
        $listeGroupes = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeTuteurs')->findByAnneeScolaire($anneeScolaire);    // tous les Groupes de l'année scolaire en cours
        $listeGroupes = array_map(function (GroupeTuteurs $t){ return $t->getGroupe();}, $listeGroupes);

        $groupes = array();
        foreach($listeGroupes as $groupe)
        {
            if(!in_array($groupe, $groupes))
                $groupes[] = $groupe;
        }

        return $this->render('CECTutoratBundle:Groupes:tous.html.twig', array('groupes' => $groupes));
    }

    /**
     * Affiche la page d'un groupe de tutorat.
     *
     * @param integer $groupe: id du groupe de tutorat
     */
    public function voirAction($groupe)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
            
        // On rassemble les séances à venir
        $seances = $this->getDoctrine()->getRepository('CECTutoratBundle:Seance')->findComingByGroupe($groupe);
        
        $lyceens = $groupe->getLyceensParAnnee();
        $tuteurs = $groupe->getTuteursparAnnee();
        
        $anneesScolaires = array();
        foreach($tuteurs as $gt)
        {
            if(!in_array($gt->getAnneeScolaire(), $anneesScolaires))
                $anneesScolaires[] = $gt->getAnneeScolaire();
        }

        usort($anneesScolaires, function(AnneeScolaire $annee, AnneeScolaire $autreAnnee) {
        if ($annee == $autreAnnee) return 0;
        return ($annee->getAnneeInferieure() < $autreAnnee->getAnneeInferieure()) ? 1 : -1;
        });
        
        // On génère le formulaire de nouvelle séance
        $nouvelleSeance = new Seance();
        $nouvelleSeanceForm = $this->createForm(new SeanceType(), $nouvelleSeance);
        $nouvelleSeance->setGroupe($groupe);
        foreach ($tuteurs as $Groupetuteur) {
            $Groupetuteur->getTuteur()->addSeance($nouvelleSeance);
            $nouvelleSeance->addTuteur($Groupetuteur->getTuteur());
        }
        
        // Par défaut, on masque le modal
        $afficherModal = false;
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST' )
        {
            $nouvelleSeanceForm->bindRequest($request);
            if ($nouvelleSeanceForm->isValid())
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($nouvelleSeance);
                $entityManager->flush();
                $this->get('session')->setFlash('success', 'La séance de tutorat a bien été ajoutée.');
                return $this->redirect($this->generateUrl('groupe', array('groupe' => $groupe->getId())));
            } else {
                $afficherModal = true;
            }
        }
        
        // On change les placeholders du formulaire de création de séance
        // pour correspondre aux infos du groupe de tutorat.
        $nouvelleSeanceFormView = $nouvelleSeanceForm->createView();
        $nouvelleSeanceFormView->getChild('lieu')->setAttribute('placeholder', $groupe->getLieu());
        $nouvelleSeanceFormView->getChild('rendezVous')->setAttribute('placeholder', $groupe->getRendezVous());
        $nouvelleSeanceFormView->getChild('debut')->setAttribute('placeholder', $groupe->getDebut()->format('H:i'));
        $nouvelleSeanceFormView->getChild('fin')->setAttribute('placeholder', $groupe->getFin()->format('H:i'));
        
        return $this->render('CECTutoratBundle:Groupes:voir.html.twig', array(
            'groupe'       => $groupe,
            'lyceens'      => $lyceens,
            'tuteurs'      => $tuteurs,
            'seances'      => $seances,
            'anneesScolaires' => $anneesScolaires,
            'nouvelle_seance_form' => $nouvelleSeanceFormView,
            'afficher_modal'       => $afficherModal,
        ));
    }
    
    /**
     * Permet l'édition d'un groupe de tutorat.
     *
     * @param integer $groupe: id du groupe de tutorat
     */
    public function editerAction($groupe)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
        
        $lyceens = $groupe->getLyceensParAnnee()->toArray();
        $lyceens = array_filter($lyceens, function(GroupeEleves $e){
            return ($e->getAnneeScolaire() == AnneeScolaire::withDate());
        });
        $lyceens = array_map(function(GroupeEleves $e){return $e->getLyceen();}, $lyceens);

        $tuteurs = $groupe->getTuteursparAnnee()->toArray();
        $tuteurs = array_filter($tuteurs, function(GroupeTuteurs $t){
            return ($t->getAnneeScolaire() == AnneeScolaire::withDate());
        });
        $tuteurs = array_map(function(GroupeTuteurs $t){return $t->getTuteur();}, $tuteurs);

        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        usort($lyceens, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        // On génère les formulaires
        $groupeForm = $this->createForm(new GroupeType(), $groupe);
        $ajouterLyceenForm = $this->createForm(new AjouterLyceenType());
        $ajouterTuteurForm = $this->createForm(new ajouterTuteurType());
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST' && $request->request->has('groupe'))
        {
            $groupeForm->bindRequest($request);
            if ($groupeForm->isValid())
            {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Les informations du groupe de tutorat ont bien été enregistrées.');
                return $this->redirect($this->generateUrl('groupe', array('groupe' => $groupe->getId())));
            }
        }
        
        return $this->render('CECTutoratBundle:Groupes:editer.html.twig', array(
            'groupe'       => $groupe,
            'lyceens'      => $lyceens,
            'tuteurs'      => $tuteurs,
            'anneeScolaire' => AnneeScolaire::withDate(),
            'groupe_form'  => $groupeForm->createView(),
            'ajouter_lyceen_form'  => $ajouterLyceenForm->createView(),
            'ajouter_tuteur_form'  => $ajouterTuteurForm->createView(),
        ));
    }
    
    /**
     * Permet de créer un nouveau groupe de tutorat
     *
     * @param integer $lycee: id du lycée auquel le nouveau groupe de tutorat
     *                        doit être associé par défaut. S'il est null, on ne sélectionne aucun lycée.
     */
    public function creerAction($lycee)
    {
        $groupe = new Groupe();
        
        // Sélectionne le lycée associé s'il est spécifié
        if ($lycee != null)
        {
            $lycee = $this->getDoctrine()->getRepository('CECTutoratBundle:Lycee')->find($lycee);
            if (!$lycee) throw $this->createNotFoundException('Impossible de trouver le lycée !');
            if (!$lycee->getPivot())
            {
                $groupe->addLycee($lycee);
            }
        }
        
        // Génère le formulaire
        $form = $this->createForm(new GroupeType(), $groupe);
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($groupe);
                $entityManager->flush();
                
                $this->get('session')->setFlash('success', 'Le groupe de tutorat a bien été créé. Vous pouvez désormais ajouter des séances, des lycéens et des tuteurs à ce groupe.');
                return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
            }
        }
        
        return $this->render('CECTutoratBundle:Groupes:creer.html.twig', array('form' => $form->createView()));
    }
    
    
    /**
     * Retire un lycéen du groupe de tutorat.
     *
     * @param integer $groupe: id du groupe de tutorat
     * @param integer $lyceen: id du lycéen
     */
    public function supprimerLyceenAction($groupe, $lyceen, $anneeScolaire)
    {
        $anneeScolaire = AnneeScolaire::withAnnees($anneeScolaire);

        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
            
        $lyceen = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');

        $groupeLyceen = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeEleves')->findOneBy(array('groupe' => $groupe, 'lyceen' => $lyceen, 'anneeScolaire' => $anneeScolaire));
        
        $em = $this->getDoctrine()->getEntityManager();

        $em->remove($groupeLyceen);
        
        $em->flush();
        return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
    }
    
    /**
     * Ajoute un lycéen au groupe de tutorat
     *
     * @param integer $groupe: id du groupe de tutorat
     * @param integer $lyceen: id du lycéen — Variable POST
     */
    public function ajouterLyceenAction($groupe)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');

        // Récupère le lycéen
        $ajouterLyceenType = new AjouterLyceenType();    // Permet de trouver le nom du formulaire — Attention, ne doit pas
                                                  // se confondre avec le nom de la route, ajouter_lyceen !
        $data = $this->getRequest()->get($ajouterLyceenType->getName());
        if (array_key_exists('lyceen', $data))
        {
            $lyceen = $data['lyceen'];
        } else {
            $this->get('session')->setFlash('error', 'Merci de spécifier un lycéen à ajouter.');
            return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
        }
        $lyceen = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        
        $groupeLyceen = new GroupeEleves();
        $groupeLyceen->setGroupe($groupe);
        $groupeLyceen->setLyceen($lyceen);
        $groupeLyceen->setAnneeScolaire(AnneeScolaire::withDate());
        $em = $this->getDoctrine()->getEntityManager();

        $em->persist($groupeLyceen);
        $em->flush();
        
        return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
    }
    
    /**
     * Retire un tuteur du groupe de tutorat.
     *
     * @param integer $groupe: id du groupe de tutorat
     * @param integer $tuteur: id du tuteur
     */
    public function supprimerTuteurAction($groupe, $tuteur, $anneeScolaire)
    {
        $anneeScolaire = AnneeScolaire::withAnnees($anneeScolaire);

        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
            
        $tuteur = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($tuteur);
        if (!$tuteur) throw $this->createNotFoundException('Impossible de trouver le tuteur !');

        $groupeTuteur = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeTuteurs')->findOneBy(array('groupe' => $groupe, 'tuteur' => $tuteur, 'anneeScolaire' => $anneeScolaire));
        
        $em = $this->getDoctrine()->getEntityManager();

        $em->remove($groupeTuteur);
        
        $em->flush();

        return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
    }
    
    /**
     * Ajoute un tuteur au groupe de tutorat
     *
     * @param integer $groupe: id du groupe de tutorat
     * @param integer $tuteur: id du tuteur — Variable POST
     */
    public function ajouterTuteurAction($groupe)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');

        // Récupère le tuteur
        $ajouterTuteurType = new AjouterTuteurType(); // Permet de trouver le nom du formulaire — Attention, ne doit pas
                                                      // se confondre avec le nom de la route, ajouter_tuteur !
        $data = $this->getRequest()->get($ajouterTuteurType->getName());
        if (array_key_exists('tuteur', $data))
        {
            $tuteur = $data['tuteur'];
        } else {
            $this->get('session')->setFlash('error', 'Merci de spécifier un tuteur à ajouter.');
            return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
        }
        $tuteur = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($tuteur);
        if (!$tuteur) throw $this->createNotFoundException('Impossible de trouver le tuteur !');
        
        $groupeTuteur = new GroupeTuteurs();
        $groupeTuteur->setGroupe($groupe);
        $groupeTuteur->setTuteur($tuteur);
        $groupeTuteur->setAnneeScolaire(AnneeScolaire::withDate());
        $em = $this->getDoctrine()->getEntityManager();

        $em->persist($groupeTuteur);
        $em->flush();
        
        return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
    }

    /**
    *
    * Affiche la liste des séances pour un groupe pour lesquelles les compte-rendus ne sont pas remplis
    *
    * @Template()
    */
    public function compteRendusAction($groupe)
    {
        $membre = $this->getUser();
        $groupe = $membre->getGroupe();
        $crARediger = array();
        if ($groupe) {
            $crARediger = $this->getDoctrine()->getRepository('CECActiviteBundle:CompteRendu')->findARedigerByGroupe($groupe);
        }

        $seances = array();
        foreach($crARediger as $cr)
        {
            if(!in_array($cr->getSeance(), $seances))
                $seances[] = $cr->getSeance();
        }

        $lyceens = $groupe->getLyceensParAnnee()->toArray();
        $lyceens = array_filter($lyceens, function(GroupeEleves $e){
            return ($e->getAnneeScolaire() == AnneeScolaire::withDate());
        });
        $lyceens = array_map(function(GroupeEleves $e){return $e->getLyceen();}, $lyceens);

        $tuteurs = $groupe->getTuteursparAnnee()->toArray();
        $tuteurs = array_filter($tuteurs, function(GroupeTuteurs $t){
            return ($t->getAnneeScolaire() == AnneeScolaire::withDate());
        });
        $tuteurs = array_map(function(GroupeTuteurs $t){return $t->getTuteur();}, $tuteurs);

        return array(
                     'seances' => $seances,
                     'groupe' => $groupe,
                     'lyceens' => $lyceens,
                     'tuteurs' => $tuteurs,
                     'anneeScolaire' => AnneeScolaire::withDate());
    }
}
