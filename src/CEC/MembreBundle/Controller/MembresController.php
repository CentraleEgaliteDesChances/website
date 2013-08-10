<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

use CEC\MembreBundle\Utility\NouveauMembreBuro;
use CEC\MembreBundle\Form\Type\NouveauMembreBuroType;
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
