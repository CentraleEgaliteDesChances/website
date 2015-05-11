<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

use CEC\MembreBundle\Utility\NouveauMembreBuro;
use CEC\MembreBundle\Form\Type\NouveauMembreBuroType;
use CEC\MembreBundle\Form\Type\MembreType;
use CEC\MembreBundle\Entity\Membre;


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

        return array('lycee' => $lycees, 'categorie'=> $categorie);
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
        
        return array(
            'membre'    => $membre,
        );
    }
	
	/**
     * Affiche le profil d'un élève.
     *
     * @param integer $id: id du membre, null pour afficher le profil du membre connecté
     *@Template()
     */
    public function voirEleveAction($id)
    {
        $eleve = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->find($id);
        if (!$eleve) throw $this->createNotFoundException('Impossible de trouver le profil !');
        
        return array(
            'eleve'    => $eleve,
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
        $membres = $this->getDoctrine()->getRepository('CECMembreBundle:Eleve')->findAll();    // tous les Membres
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
            $form->bindRequest($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($membre);
                $entityManager->flush();
                
                // Envoyer un message
                $email = \Swift_Message::newInstance()
                    ->setSubject("Bienvenue sur le site interne de CEC !")
                    ->setFrom(array("notification@cec-ecp.com" => "Notification CEC"))
                    ->setTo(array($membre->getEmail() => $membre->__toString()))
                    ->setBody(
                        $this->renderView('CECMembreBundle:Mail:bienvenue.html.twig',
                            array(
                                'membre' => $membre,
                                'mot_de_passe' => $motDePasse,
                                'base_url' => $_SERVER['HTTP_HOST'],
                            )),
                        'text/html');
                $this->get('mailer')->send($email);
                
                $this->get('session')->setFlash('success', "'" . $membre . "' a bien été ajouté. Un email de bienvenue, contenant son mot de passe provisoire '" . $motDePasse . "', lui a été envoyé.");
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
        
        $this->get('session')->setFlash('success', 'Le membre a bien été définitivement supprimé.');
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
            $form->bindRequest($request);
            if ($form->isValid()) {
                $nouveauMembreBuro->getMembre()->setBuro(true);
                $nouveauMembreBuro->getMembre()->updateRoles();
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', $nouveauMembreBuro->getMembre() . " bénéficie désormais des privilèges du buro de l'association !");
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
     * @param CEC\MembreBundle\Entity\Membre $membre Membre à retirer du buro
     *
     * @Template()
     */
    public function supprimerMembreBuroAction(Membre $membre) {
        $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($membre);
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver le profil !');
        
        $membre->setBuro(false);
        $membre->updateRoles();
        $this->getDoctrine()->getEntityManager()->flush();
        
        return $this->redirect($this->generateUrl('passations'));
    }
    
}
