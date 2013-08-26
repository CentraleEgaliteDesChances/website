<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Route("/membres")
     * @Template()
     */
    public function tousAction()
    {
        $membres = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->findAll();    // tous les Membres
        return array('membres' => $membres);
    }

    /**
     * Affiche le profil d'un membre.
     *
     * @param integer $membre: id du membre, null pour afficher le profil du membre connecté
     *
     * @Route("/membres/{membre}", requirements = { "membre" = "\d+" })
     * @Template()
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
     * Permet de créer un nouveau membre.
     * Affiche un formulaire permettant d'entrer le nom, le prénom, l'adresse email,
     * le numéro de téléphone, et la promotion du nouveau membre. Un bouton permet
     * d'enregistrer le nouveau membre, et un bouton Annuler permet de revenir à la liste.
     *
     * @Route("/membres/creation")
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
                
                $this->get('session')->setFlash('success', "'" . $membre . "' a bien été ajouté. Un email de bienvenu, contenant son mot de passe provisoire '" . $motDePasse . "', lui a été envoyé.");
                return $this->redirect($this->generateUrl('cec_membre_membres_creer'));
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
     * @Route("/membres/suppression/{membre}")
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
        return $this->redirect($this->generateUrl('cec_membre_membres_tous'));
    }
    
    
    /**
     * Permet d'effectuer les passations du Buro.
     * La page affiche tous les membres bénéficiant du statut de membre du buro, et permet
     * aux membres du buro d'attribuer à d'autres membres ce statut. A utiliser lors des passations.
     *
     * @Route("/membres/passations")
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
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', $nouveauMembreBuro->getMembre() . " bénéficie désormais des privilèges du buro de l'association !");
                return $this->redirect($this->generateUrl('cec_membre_membres_passations'));
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
     * @Route("/membres/passations/retirer_membre/{membre}")
     * @Template()
     */
    public function supprimerMembreBuroAction(Membre $membre) {
        $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->find($membre);
        if (!$membre) throw $this->createNotFoundException('Impossible de trouver le profil !');
        
        $membre->setBuro(false);
        $this->getDoctrine()->getEntityManager()->flush();
        
        return $this->redirect($this->generateUrl('cec_membre_membres_passations'));
    }
    
}
