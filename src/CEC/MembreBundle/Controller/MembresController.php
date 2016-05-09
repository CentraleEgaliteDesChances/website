<?php

namespace CEC\MembreBundle\Controller;

use CEC\MembreBundle\Entity\Eleve;
use CEC\MembreBundle\Entity\EleveCollection;
use CEC\MembreBundle\Form\Type\EleveGestionType;
use CEC\MembreBundle\Form\Type\ElevesGestionType;
use CEC\MembreBundle\Form\Type\TableEleveType;
use Doctrine\Common\Collections\ArrayCollection;
use PhpParser\Node\Expr\List_;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

use CEC\MembreBundle\Utility\NouveauMembreBuro;
use CEC\MembreBundle\Form\Type\NouveauMembreBuroType;
use CEC\MembreBundle\Form\Type\MembreType;
use CEC\MembreBundle\Entity\Membre;

use CEC\TutoratBundle\Entity\GroupeEleves;
use CEC\TutoratBundle\Entity\GroupeTuteurs;

use CEC\MainBundle\AnneeScolaire\AnneeScolaire;


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
     *
     * @Template()
     */
    public function tousLyceeAction($lycee, $categorie)
    {
        $lycees = $this->getDoctrine()->getRepository("CECTutoratBundle:Lycee")->find($lycee);
        if(!$lycees) throw new $this->createNotFoundException("Pas de lycée trouvé");

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
     *@Template()
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

    /**
     * Permet de créer un nouveau membre.
     * Affiche un formulaire permettant d'entrer le nom, le prénom, l'adresse email,
     * le numéro de téléphone, et la promotion du nouveau membre. Un bouton permet
     * d'enregistrer le nouveau membre, et un bouton Annuler permet de revenir à la liste.
     *
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */
    public function creerAction()
    {
        $membre = new Membre();
        $motDePasse = substr(str_shuffle(MD5(microtime())), 0, 10);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($membre);
        $membre->setMotDePasse($encoder->encodePassword($motDePasse, $membre->getSalt()));

        $form = $this->createForm(new MembreType(), $membre);

        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                //Création de l'username du membre
                $nom = $form->get('nom')->getData();
                $prenom= $form->get('prenom')->getData();
                $membresExistant = $this
                    ->getDoctrine()
                    ->getRepository('CECMembreBundle:Membre')
                    ->findByUsername($nom,$prenom);
                if (count($membresExistant) > 0)
                {
                    $membre->setUsername($prenom. ' ' . $nom.(count($membresExistant)+1));
                }
                else
                {
                    $membre->setUsername($prenom . ' ' .$nom);
                }
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($membre);
                $entityManager->flush();

                // Envoyer un message de confirmation
                $this->get('cec.mailer')->sendInscription($membre, $motDePasse, $_SERVER['HTTP_HOST']);

                $this->get('session')->getFlashBag()->add('success', "'" . $membre . "' a bien été ajouté. Un email de bienvenue, contenant son mot de passe provisoire '" . $motDePasse . "', lui a été envoyé.");
                return $this->redirect($this->generateUrl('creer_membre'));
            }
        }
        return array(
            'form' => $form->createView(),
        );
    }





    /**
     * Supprimer un membre de manière définitive.
     *
     * @param integer $membre Id du membre à supprimer.
     *
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */
    public function supprimerAction($membre)
    {
        $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($membre);
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver le profil !');

        $entityManager = $this->getDoctrine()->getEntityManager();
        $entityManager->remove($membre);
        $entityManager->flush();

        $this->get('session')->getFlashBag()->add('success', 'Le membre a bien été définitivement supprimé.');
        return $this->redirect($this->generateUrl('voir_tous_membres'));
    }


    /**
     * Permet d'effectuer les passations du Buro.
     * La page affiche tous les membres bénéficiant du statut de membre du buro, et permet
     * aux membres du buro d'attribuer à d'autres membres ce statut. A utiliser lors des passations.
     *
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */
    public function passationsAction()
    {
        $membresBuro = $this->getDoctrine()->getRepository("CECMembreBundle:Membre")->findBuro();

        $nouveauMembreBuro = new NouveauMembreBuro();
        $form = $this->createForm(new NouveauMembreBuroType(), $nouveauMembreBuro);

        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $nouveauMembreBuro->getMembre()->setBuro(true);
                $nouveauMembreBuro->getMembre()->updateRoles();
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->getFlashBag()->add('success', $nouveauMembreBuro->getMembre() . " bénéficie désormais des privilèges du buro de l'association !");
                $this->get('cec.mailer')->sendPassations($membre, $_SERVER['HTTP_HOST']);
                return $this->redirect($this->generateUrl('passations'));
            }
        }

        return array(
            'membres_buro' => $membresBuro,
            'form' => $form->createView(),
        );
    }

    /**
     * Retire les privilèges du buro à un membre.
     *
     * @param CEC\Membre\MBundle\Entity\Membre $membre Membre à retirer du buro
     *
     * @Template()
     *
     */
    public function supprimerMembreBuroAction(Membre $membre) {
        $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($membre);
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver le profil !');

        $membre->setBuro(false);
        $membre->updateRoles();
        $this->getDoctrine()->getEntityManager()->flush();

        return $this->redirect($this->generateUrl('passations'));
    }

    /**
     * Permet de gérer l'ensemble des lycéens.
     * Cette page n'est accessible qu'aux membres du buro
     * La page affiche tous les lycéens inscrits sur le site et donne la possibilité de :
     * - trier la liste selon le paramètre voulu
     * - récuperer un excel de tous les lycéens
     * - dire si un élève a bien rendu ses documents
     * @return array
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */

    public function gestionElevesAction()
    {
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            if (empty($_POST['checkbox'])) {


            }
            elseif (!(isset($_POST['documents_recus']))) {

            }
            else {

                $em = $this->getDoctrine()->getManager();
                foreach ($_POST['checkbox'] as $item) {
                    $eleve = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($item);
                    switch ($_POST['documents_recus']){
                        case 'charte_eleve':
                            $eleve->setCharteEleveRendue(true);
                            break;
                        case 'autorisation_parentale':
                            $eleve->setAutorisationParentaleRendue(true);
                            break;
                        case 'droit_image':
                            $eleve->setDroitImageRendue(true);
                            break;
                        case 'tous':
                            $eleve->setCharteEleveRendue(true);
                            $eleve->setAutorisationParentaleRendue(true);
                            $eleve->setDroitImageRendue(true);
                            break;
                        default:
                    }

                    $em->persist($eleve);
                }
                $em->flush();
            }
        }
        $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderByLyceeAndNiveau();
        return array('eleves' => $eleves);
    }

    public function gestionElevesTestAction()
    {
    }



    /**
     * Permet de gérer les documents d'un lycéen
     * Cette page n'est accessible qu'aux membres du buro
     * La page affiche tous les lycéens inscrits sur le site et donne la possibilité de :
     * - trier la liste selon le paramètre voulu
     * - récuperer un excel de tous les lycéens
     * - dire si un élève a bien rendu ses documents
     *
     * @param CEC\MembreBundle\Entity\Eleve
     * @return array
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */
    public function gestionEleveAction($eleveid)
    {
        $eleve = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($eleveid);
        if (!$eleve) throw $this->createNotFoundException('Impossible de trouver le profil !');
        $form = $this->createForm(EleveGestionType::class,$eleve);
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->getFlashBag()->add('success', "Documents de ".$eleve->getPrenom() . " mis à jour !");
            }
        }
        return array(
            'eleve' => $eleve,
            'form' => $form->createView(),
        );

    }


}
