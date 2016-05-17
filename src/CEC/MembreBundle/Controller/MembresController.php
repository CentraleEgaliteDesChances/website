<?php

namespace CEC\MembreBundle\Controller;

use CEC\MembreBundle\Entity\DossierInscription;
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
                $elevesExistant = $this
                    ->getDoctrine()
                    ->getRepository('CECMembreBundle:Eleve')
                    ->findByUsername($nom,$prenom);
                $membresExistant = $this
                    ->getDoctrine()
                    ->getRepository('CECMembreBundle:Membre')
                    ->findByUsername($nom,$prenom);
                $professeursExistant = $this
                    ->getDoctrine()
                    ->getRepository('CECMembreBundle:Professeur')
                    ->findByUsername($nom,$prenom);
                $count = count($elevesExistant) + count($membresExistant) + count($professeursExistant);
                if ($count > 0)
                {
                    $membre->setUsername($prenom.$nom.($count + 1));
                }
                else
                {
                    $membre->setUsername($prenom .$nom);
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
     * Permet de générer un excel comportant la liste des élèves ainsi que toutes les informations qui les concernent
     *
     * @return StreamedResponse
     * @Secure(roles = "ROLE_BURO")
     */
    public function excelElevesAction()
    {
        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'r+');
            //Ajout de l'entete
            $tab = array(
                'Id',
                'Nom',
                'Prenom',
                'Email',
                'Lycee',
                'Classe',
                'Adresse',
                'Ville',
                'Code Postal',
                'Telephone',
                'Date de naissance',
                'Charte Eleve',
                'Autorisation Parentale',
                'Droit image',
                'Inscrit sur le site',
                'Profession Père',
                'Profession Mère',
                'Telephone Parent',
                'Email Parent',
                'Statut des parents',
                'Nombre personnes à charge',
                'Nombre d\'enfants',
                'Caractéristiques enfants',
                'Bourses',
                'Nombre d\'année à CEC',
                'Raison 1 : J\ai déjà participé au programme',
                'Raison 2 : J\'ai été encouragé par un proche',
                'Raison 3 : Par curiosité',
                'Raison 4 : Pour le programme éducatif',
                'Raison 5 : Pour les projets et sorties',
                'Raison 6 : Mon lycée',
                'Proche qui a encouragé',
                'Matières préférées',
                'Matières moins appréciées',
                'Idée orientation post_bac',
                'Idée métier après études',
                'Aisance à l\'oral',
                'Aisance dans le système scolaire',
                'Capacité à obtenir les études souhaitées',
                'Information sur l\'enseignement post-bac',
                'Attachement à l\'actualité',
                'Intérêt sciences',
                'Loisirs et activités extrascolaires',
                'Pratique : aller au musée',
                'Pratique : aller au théâtre',
                'Pratique : aller au cinéma',
                'Pratique : regarder le journal télévisé',
                'Pratique : lire les journaux',
                'Pratique : Lire',
                'Projets qui l\'intéresse',
                'Langues vivantes',
                'Correspondant étranger',
                'Interêt Europen',
                'Voyages à l\'étranger'
            );
            fputcsv($handle, $tab,';');
            //Recherche dans la base de donnée
            $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAll();
            foreach ($eleves as $eleve) {

                $dossier = $eleve->getDossierInscription();
                //Gère le cas où l'élève a compléter son dossier d'inscription
                if ($dossier == null) {
                    $dossier = new DossierInscription();
                }
                //Génération de la case des projets qui intéressent l'élève
                $projetQuiInteresse = "";
                foreach ($dossier->getProjetsCecInterets() as $projet) {
                    $projetQuiInteresse = $projetQuiInteresse.$projet.",";
                }
                //Génération de la ligne
                $tab = array(
                    $eleve->getId(),
                    $eleve->getNom(),
                    $eleve->getPrenom(),
                    $eleve->getMail(),
                    $eleve->getLycee(),
                    $eleve->getNiveau(),
                    $eleve->getAdresse(),
                    $eleve->getVille(),
                    $eleve->getCodePostal(),
                    $eleve->getTelephone(),
                    $eleve->getDatenaiss()->format('d/m/Y'),
                    $eleve->isCharteEleveRendue()?'Oui':'Non',
                    $eleve->isAutorisationParentaleRendue()?'Oui':'Non',
                    $eleve->isDroitImageRendue()?'Oui':'Non',
                    'Oui',
                    $dossier->getProfessionPere(),
                    $dossier->getProfessionMere(),
                    $dossier->getTelephoneParent(),
                    $dossier->getMailParent(),
                    $dossier->getStatutParents(),
                    $dossier->getNombrePersonnesACharge(),
                    $dossier->getNombreEnfants(),
                    $dossier->getEnfants(),
                    $dossier->getBourses(),
                    $dossier->getNombreAnneeChezCec(),
                    $dossier->isRaisonInscriptionCecParticipeAuProgramme()?'Oui':'Non',
                    $dossier->isRaisonInscriptionCecEncourageParProche()?'Oui':'Non',
                    $dossier->isRaisonInscriptionCecCuriosite()?'Oui':'Non',
                    $dossier->isRaisonInscriptionCecProgrammeEducatif()?'Oui':'Non',
                    $dossier->isRaisonInscriptionCecSortiesProjets()?'Oui':'Non',
                    $dossier->isRaisonInscriptionCecLycee()?'Oui':'Non',
                    $dossier->getProcheQuiAEncouragePourCec(),
                    $dossier->getMatieresPreferees(),
                    $dossier->getMatieresDetestees(),
                    $dossier->getIdeeOrientationPostBac(),
                    $dossier->getIdeeMetier(),
                    $dossier->getAisanceOral(),
                    $dossier->getAisanceSystemeScolaire(),
                    $dossier->getCapaciteObtentionEtudesSouhaitees(),
                    $dossier->getInformationEnseignementSuperieur(),
                    $dossier->getAttachementActualites(),
                    $dossier->getInteretScience(),
                    $dossier->getActivitesExtrascolaires(),
                    $dossier->getPratiqueMusee(),
                    $dossier->getPratiqueTheatre(),
                    $dossier->getPratiqueCinema(),
                    $dossier->getPratiqueJournalTelevise(),
                    $dossier->getPratiqueJournaux(),
                    $dossier->getPratiqueLecture(),
                    $projetQuiInteresse,
                    $dossier->getLangueVivante(),
                    $dossier->getCorrespondantEtranger(),
                    $dossier->getInteretEuropen(),
                    $dossier->getVoyagesRealises()
                );
                fputcsv($handle, $tab,';');
            }
            fclose($handle);

        });
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        //$response->headers->set('Content-Disposition','attachment; filename="excel_eleves.csv"');
        
        return $response;
    }






    /**
     * Supprimer un membre de manière définitive.
     *
     * @param integer $membre Id du membre à supprimer.
     * @return RedirectResponse
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
     * @param Membre $membre Membre à retirer du buro
     *
     * @return RedirectResponse
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
     * Auteur : Jimmy EUng
     *
     * Permet de gérer l'ensemble des lycéens.
     * Cette page n'est accessible qu'aux membres du buro
     * La page affiche tous les lycéens inscrits sur le site et donne la possibilité de :
     * - trier la liste selon le paramètre voulu
     * - récuperer un excel de tous les lycéens
     * - dire si un élève a bien rendu ses documents
     *
     *
     * @return array
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */

    public function gestionElevesAction($sorting)
    {
        //On génère la liste des élèves affichés triés selon le critère voulu
        switch ($sorting) {
            case 'nom':
                $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderByNom();
                break;
            case 'prenom':
                $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderByPrenom();
                break;
            case 'lycee':
                $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderByLycee();
                break;
            case 'niveau':
                $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderByNiveau();
                break;
            case 'charte_eleve':
                $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderByCharteEleve();
                break;
            case 'autorisation_parentale':
                $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderByAutorisationParentale();
                break;
            case 'droit_image':
                $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderByDroitImage();
                break;
            default:
                $eleves = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAllOrderById();

        }

        //Ici, on traite les données soumises par le formulaire, par contre, ce n'est pas un formulaire  crée à partir de Symfony
        //c'est un formulaire HTML d'où la nécessité de traiter les données avec la variable $_POST

        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $compteur = 0;
            if (empty($_POST['checkbox'])) {
                //ne fait rien si aucune ligne n'est sélectionnée
            }
            elseif (!(isset($_POST['documents_recus']))) {
                //ne fait rien si aucun type document n'est sélectionné
            }
            else {
                //Modification des attributs des élèves

                $em = $this->getDoctrine()->getManager();
                foreach ($_POST['checkbox'] as $item) {
                    $eleve = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($item);
                    switch ($_POST['documents_recus']){
                        case 'charte_eleve':
                            if (!($eleve->isCharteEleveRendue())) {
                                $compteur = $compteur +1;
                                $eleve->setCharteEleveRendue(true);
                            }
                            break;
                        case 'autorisation_parentale':
                            if (!($eleve->isAutorisationParentaleRendue())) {
                                $compteur = $compteur +1;
                                $eleve->setAutorisationParentaleRendue(true);
                            }
                            break;
                        case 'droit_image':
                            if (!($eleve->isDroitImageRendue())) {
                                $compteur = $compteur +1;
                                $eleve->setDroitImageRendue(true);
                            }
                            break;
                        case 'tous':
                            if (!($eleve->isCharteEleveRendue() and $eleve->isAutorisationParentaleRendue() and $eleve->isDroitImageRendue())) {
                                $compteur = $compteur +1;
                            }
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
            //Affichage d'une notification
            $textFlashBag = "Aucun élève mis à jour";
            if ($compteur == 1) {
                $textFlashBag = "Documents de 1 élève mis à jour";
                $this->get('session')->getFlashBag()->add('success', $textFlashBag);
            }
            elseif ($compteur > 1) {
                $textFlashBag = "Documents de ".$compteur." élèves mis à jour";
                $this->get('session')->getFlashBag()->add('success', $textFlashBag);
            }
            else {
                $this->get('session')->getFlashBag()->add('info', $textFlashBag);
            }
        }


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
