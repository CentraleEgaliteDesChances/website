<?php
/**
 * Created by PhpStorm.
 * User: eung
 * Date: 02/07/16
 * Time: 16:08
 */

namespace CEC\MembreBundle\Controller;


use CEC\MembreBundle\Entity\DossierInscription;
use CEC\MembreBundle\Entity\Membre;
use CEC\MembreBundle\Form\Type\EleveGestionType;
use CEC\MembreBundle\Form\Type\MembreType;
use CEC\MembreBundle\Form\Type\NouveauMembreBuroType;
use CEC\MembreBundle\Form\Type\SecteurMembreType;
use CEC\MembreBundle\Utility\NouveauMembreBuro;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdministrationController extends Controller
{
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
                $nom = str_replace(' ','' ,$form->get('nom')->getData());
                $prenom= str_replace(' ','' ,$form->get('prenom')->getData());
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
                $parentsExistant = $this
                    ->getDoctrine()
                    ->getRepository('CECMembreBundle:ParentEleve')
                    ->findByUsername($nom, $prenom);
                $count = count($elevesExistant) + count($membresExistant) + count($professeursExistant) + count($parentsExistant);
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
                'Dossier rendu',
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
                $dossierRempli = "Oui";
                //Gère le cas où l'élève a compléter son dossier d'inscription
                if ($dossier == null) {
                    $dossier = new DossierInscription();
                    $dossierRempli = "Non";
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
                    $dossierRempli,
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
     * Permet de générer un excel comportant la liste des élèves ainsi que toutes les informations qui les concernent
     *
     * @return StreamedResponse
     * @Secure(roles = "ROLE_BURO")
     */
    public function excelParentsAction()
    {
        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'r+');
            //Ajout de l'entete
            $tab = array(
                'Id',
                'Nom',
                'Prenom',
                'Email',
                'Telephone',
                'Enfants'
            );
            fputcsv($handle, $tab,';');
            //Recherche dans la base de donnée
            $parents = $this->getDoctrine()->getRepository('CECMembreBundle:ParentEleve')->findAll();
            foreach ($parents as $parent) {

                //Génération de la case des projets qui intéressent l'élève
                $enfants = "";
                foreach ($parent->getEleves() as $enfant) {
                    $enfants = $enfants.$enfant.",";
                }
                //Génération de la ligne
                $tab = array(
                    $parent->getId(),
                    $parent->getNom(),
                    $parent->getPrenom(),
                    $parent->getMail(),
                    $parent->getTelephone(),
                    $enfants
                );
                fputcsv($handle, $tab,';');
            }
            fclose($handle);

        });
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        //$response->headers->set('Content-Disposition','attachment; filename="excel_parents.csv"');

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
    /**
     * Auteur : Jimmy EUng
     *
     * Permet de définir les secteurs d'un membre
     *
     *
     * @return array
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */
    public function gestionMembreSecteursAction($membreid)
    {
        $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($membreid);
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver le profil !');

        $form = $this->createForm(new SecteurMembreType(), $membre);
        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->getFlashBag()->add('success', 'Les modifications ont bien été enregistrées.');
                return $this->redirect($this->generateUrl('gestion_membres'));
            }
        }
        return array(
            'membre' => $membre,
            'form' => $form->createView()
        );

    }

    /**
     * Auteur : Jimmy EUng
     *
     * Permet de définir si un membre est VP Lycee et les lycées dont il est VP.
     * @return array
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */
    public function gestionMembreVPLyceeAction()
    {

    }

    /**
     * Auteur : Jimmy EUng
     *
     *
     * @return array
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */
    public function gestionMembresAction()
    {
        $membres = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->findAll();    // tous les Membres
        return array('membres' => $membres);
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
    public function gestionParentsAction()
    {
        $parents = $this->getDoctrine()->getRepository('CECMembreBundle:ParentEleve')->findAll();

        return array(
            'parents' =>$parents
        );
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

    /**
     * Permet de supprimer un lycéen
     *
     * @param CEC\MembreBundle\Entity\Eleve
     * @return array
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */

    public function supprimerEleveAction($eleveid)
    {
        $eleve = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($eleveid);
        if (!$eleve) throw $this->createNotFoundException('Impossible de trouver le profil !');
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $entityManager = $this->getDoctrine()->getEntityManager();
            $entityManager->remove($eleve);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('success', $eleve->getUsername().' a bien été définitivement supprimé.');
            return $this->redirect($this->generateUrl('gestion_eleves'));
        }
        return array(
            'eleve' =>$eleve,
        );
    }

    /**
     * Permet de supprimer un parent
     *
     * @param CEC\MembreBundle\Entity\Parent
     * @return array
     * @Template()
     * @Secure(roles = "ROLE_BURO")
     */
    public function supprimerParentAction($parentid)
    {
        $parent = $this->getDoctrine()->getRepository('CECMembreBundle:ParentEleve')->find($parentid);
        if (!$parent) throw $this->createNotFoundException('Impossible de trouver le profil !');
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $entityManager = $this->getDoctrine()->getEntityManager();
            $entityManager->remove($parent);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('success', $parent->getUsername().' a bien été définitivement supprimé.');
            return $this->redirect($this->generateUrl('gestion_parents'));
        }
        return array(
            'parent' =>$parent,
        );
    }

    

}