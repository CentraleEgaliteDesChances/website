<?php

namespace CEC\TutoratBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CEC\TutoratBundle\Entity\Groupe;
use CEC\TutoratBundle\Entity\Seance;

use CEC\TutoratBundle\Form\Type\GroupeType;
use CEC\TutoratBundle\Form\Type\AjouterLyceenType;
use CEC\TutoratBundle\Form\Type\AjouterTuteurType;
use CEC\TutoratBundle\Form\Type\SeanceType;

class GroupesController extends Controller
{
    /**
     * Affiche la liste des groupes de tutorat
     */
    public function tousAction()
    {
        $groupes = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->findAll();    // tous les Groupes
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
        
        $lyceens = $groupe->getLyceens()->toArray();
        $tuteurs = $groupe->getTuteurs()->toArray();
        // On trie les tuteurs et les lycéens par ordre alphabétique
        usort($tuteurs, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        usort($lyceens, function($a, $b) {
            return strcmp($a->getNom(), $b->getNom());
        });
        
        // On génère le formulaire de nouvelle séance
        $nouvelleSeance = new Seance();
        $nouvelleSeanceForm = $this->createForm(new SeanceType(), $nouvelleSeance);
        $nouvelleSeance->setGroupe($groupe);
        foreach ($groupe->getTuteurs() as $tuteur) {
            $tuteur->addSeance($nouvelleSeance);
            $nouvelleSeance->addTuteur($tuteur);
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
        
        $lyceens = $groupe->getLyceens()->toArray();
        $tuteurs = $groupe->getTuteurs()->toArray();
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
    public function supprimerLyceenAction($groupe, $lyceen)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
            
        $lyceen = $this->getDoctrine()->getRepository('CECTutoratBundle:Lyceen')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        
        $lyceen->setGroupe(null);
        
        $this->getDoctrine()->getEntityManager()->flush();
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
        $lyceen = $this->getDoctrine()->getRepository('CECTutoratBundle:Lyceen')->find($lyceen);
        if (!$lyceen) throw $this->createNotFoundException('Impossible de trouver le lycéen !');
        
        $lyceen->setGroupe($groupe);
        $this->getDoctrine()->getEntityManager()->flush();
        
        return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
    }
    
    /**
     * Retire un tuteur du groupe de tutorat.
     *
     * @param integer $groupe: id du groupe de tutorat
     * @param integer $tuteur: id du tuteur
     */
    public function supprimerTuteurAction($groupe, $tuteur)
    {
        $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
        if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe de tutorat !');
            
        $tuteur = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($tuteur);
        if (!$tuteur) throw $this->createNotFoundException('Impossible de trouver le tuteur !');
        
        $tuteur->setGroupe(null);
        
        $this->getDoctrine()->getEntityManager()->flush();
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
        
        $tuteur->setGroupe($groupe);
        $this->getDoctrine()->getEntityManager()->flush();
        
        return $this->redirect($this->generateUrl('editer_groupe', array('groupe' => $groupe->getId())));
    }
}
