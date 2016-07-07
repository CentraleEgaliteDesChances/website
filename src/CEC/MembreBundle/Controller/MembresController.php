<?php

namespace CEC\MembreBundle\Controller;

use CEC\MembreBundle\Entity\DossierInscription;
use CEC\MembreBundle\Entity\Eleve;

use Doctrine\Common\Collections\ArrayCollection;
use PhpParser\Node\Expr\List_;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

use CEC\MembreBundle\Utility\NouveauMembreBuro;
use CEC\MembreBundle\Form\Type\NouveauMembreBuroType;
use CEC\MembreBundle\Form\Type\MembreType;
use CEC\MembreBundle\Entity\Membre;

use CEC\TutoratBundle\Entity\GroupeEleves;
use CEC\TutoratBundle\Entity\GroupeTuteurs;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;
use Symfony\Component\HttpFoundation\StreamedResponse;


class MembresController extends Controller
{
    /**
     * Affiche la liste de tous les membres.
     * Cette page affiche simplement la liste de tous les membres enregistrés sur le site internet.
     *
     * @Template()
     */
    public function tousAction()
    {
        $membres = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->findAll();    // tous les Membres
        return array('membres' => $membres);
    }

    /**
     * Affiche la liste de tous les tuteurs d'un lycée
     * @param integer $lycee : id du lycée
     * @param string $categorie : indique si c'est tuteurs/eleves/professeurs
     * @return array
     * @Template()
     */
    public function tousLyceeAction($lycee, $categorie)
    {
        $lycees = $this->getDoctrine()->getRepository("CECTutoratBundle:Lycee")->find($lycee);
        if(!$lycees) throw $this->createNotFoundException("Pas de lycée trouvé");

        return array('lycee' => $lycees, 'categorie'=> $categorie, 'anneeScolaire'=> AnneeScolaire::withDate());
    }



    /**
     * Affiche le profil d'un membre.
     *
     * @param integer $membre: id du membre, null pour afficher le profil du membre connecté
     *@Template()
     */
    public function voirAction($membre)
    {
        $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($membre);
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver le profil !');

        $tutorat = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeTuteurs')->findByTuteur($membre);

        usort($tutorat, function(GroupeTuteurs $g1, GroupeTuteurs $g2) {
            if ($g1->getAnneeScolaire() == $g2->getAnneeScolaire()) return 0;
            return ($g1->getAnneeScolaire()->getAnneeInferieure() < $g2->getAnneeScolaire()->getAnneeInferieure()) ? 1 : -1;
        });

        return array(
            'membre'    => $membre, 'tutorat' => $tutorat
        );
    }

    /**
     * Affiche le profil d'un élève.
     * @return array
     * @param integer $id: id du membre, null pour afficher le profil du membre connecté
     * @Template()
     */
    public function voirEleveAction($id)
    {
        $eleve = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($id);
        if (!$eleve) throw $this->createNotFoundException('Impossible de trouver le profil !');

        $tutorat = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeEleves')->findByLyceen($eleve);

        usort($tutorat, function(GroupeEleves $g1, GroupeEleves $g2) {
            if ($g1->getAnneeScolaire() == $g2->getAnneeScolaire()) return 0;
            return ($g1->getAnneeScolaire()->getAnneeInferieure() < $g2->getAnneeScolaire()->getAnneeInferieure()) ? 1 : -1;
        });

        return array(
            'eleve'    => $eleve, 'tutorat' => $tutorat
        );
    }

    /**
     * Affiche le profil d'un parent.
     * @return array
     * @param integer $id: id du membre, null pour afficher le profil du membre connecté
     * @Template()
     */

    public function voirParentAction($id)
    {
        $parent = $this->getDoctrine()->getRepository('CECMembreBundle:ParentEleve')->find($id);
        if (!$parent) throw $this->createNotFoundException('Impossible de trouver le profil !');

        return array(
            'parent'    => $parent, 'eleves' => $parent->getEleves()
        );
    }

    /**
     * Affiche la liste de tous les élèves.
     * Cette page affiche simplement la liste de tous les membres enregistrés sur le site internet.
     *
     * @Template()
     */
    public function tousParentAction()
    {
        $parents = $this->getDoctrine()->getRepository('CECMembreBundle:ParentEleve')->findAll();
        return array('parents' => $parents);
    }

    /**
     * Affiche la liste de tous les élèves.
     * Cette page affiche simplement la liste de tous les membres enregistrés sur le site internet.
     *
     * @Template()
     */
    public function tousEleveAction()
    {
        $membres = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderByLyceeAndNiveau();    // tous les Membres
        return array('eleves' => $membres);
    }

    /**
     * Affiche le profil d'un professeur.
     *
     * @param integer $id: id du membre, null pour afficher le profil du membre connecté
     *@Template()
     */
    public function voirProfesseurAction($id)
    {
        $professeur = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->find($id);
        if (!$professeur) throw $this->createNotFoundException('Impossible de trouver le profil !');

        return array(
            'professeur'    => $professeur,
        );
    }

    /**
     * Affiche la liste de tous les professeurs.
     * Cette page affiche simplement la liste de tous les membres enregistrés sur le site internet.
     *
     * @Template()
     */
    public function tousProfesseurAction()
    {
        $membres = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->findAll();    // tous les Membres
        return array('professeurs' => $membres);
    }

    /**
     * Menu des pages d'affichage de tous les membres/eleves/professeurs
     *
     * @Template()
     */
    public function menuAction($request)
    {
        $path = $request->getPathInfo();
        $tuteurs = False;
        $lyceens = False;
        $professeurs = False;

        $cordees = $this->getDoctrine()->getRepository('CECTutoratBundle:Cordee')->findAll();

        if(preg_match("#tuteurs#", $path))
            $tuteurs = True;
        else if(preg_match("#eleves#", $path))
            $lyceens = True;
        else if(preg_match("#professeurs#", $path))
            $professeurs = True;


        return array(
            'request' => $request,
            'cordees' => $cordees,
            'tuteurs' => $tuteurs,
            'lyceens' => $lyceens,
            'professeurs' => $professeurs);
    }







}
