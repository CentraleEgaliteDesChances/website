<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\MembreBundle\Form\Type\InfosMembreType;
use CEC\MembreBundle\Form\Type\MotDePasseMembreType;
use CEC\MembreBundle\Form\Type\SecteursMembreType;
use CEC\MembreBundle\Form\Type\GroupeMembreType;

class ReglagesController extends Controller
{
    /**
     * Modification des informations personnelles.
     * Cette page permet de modifier les informations personnelles d'un membre (nom, prénom,
     * adresse électronique, numéro de téléphone et promotion). Elle permet aussi de changer le mot de passe.
     *
     * @Template()
     */
    public function infosAction()
    {
        // On récupère l'utilisateur actuel
        $membre = $this->getUser();
        if (!$membre) throw $this->createNotFoundException('L\'utilisateur actif n\'a pas pu être trouvé !');
        
        $nomInformationsGenerales = 'InfosMembre';
        $infomationsGenerales = $this->get('form.factory')
            ->createNamedBuilder($nomInformationsGenerales, new InfosMembreType(), $membre)
            ->getForm();
            
        $nomMotDePasse = 'MotDePasseMembre';
        $motDePasse = $this->get('form.factory')
            ->createNamedBuilder($nomMotDePasse, new MotDePasseMembreType())
            ->getForm();
        
        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            if ($request->request->has($nomInformationsGenerales)) {
                $infomationsGenerales->bindRequest($request);
                if ($infomationsGenerales->isValid()) {
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->get('session')->setFlash('success', 'Les modifications ont bien été enregistrées.');
                    return $this->redirect($this->generateUrl('cec_membre_reglages_infos'));
                }
            }
            
            if ($request->request->has($nomMotDePasse)) {
                $motDePasse->bindRequest($request);
                if ($motDePasse->isValid()) {
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($membre);
                    $data = $motDePasse->getData();
                    $password = $encoder->encodePassword($data['motDePasse'], $membre->getSalt());
                    $membre->setMotDePasse($password);
                    
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->get('session')->setFlash('success', 'Le mot de passe a bien été modifié.');
                    return $this->redirect($this->generateUrl('cec_membre_reglages_infos'));
                }
            }
        }
        
        return array(
            'informations_generales' => $infomationsGenerales->createView(),
            'mot_de_passe'           => $motDePasse->createView(),
        );
    }
    
    /**
     * Sélection de son groupe de tutorat régulier.
     * @Template()
     */
    public function groupeAction()
    {
        $membre = $this->getUser();
        $form = $this->createForm(new GroupeMembreType(), $membre);
        
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Votre groupe de tutorat a bien été modifié.');
                return $this->redirect($this->generateUrl('cec_membre_reglages_groupe'));
            }
        }
        
        return array('form' => $form->createView());
    }
    
    /**
     * Sélection des secteurs dans lequel le membre s'implique.
     *@Template()
     */
    public function secteursAction()
    {
        // On récupère l'utilisateur
        $membre = $this->getUser();
        if (!$membre) throw $this->createNotFoundException('L\'utilisateur actif n\'a pas pu être trouvé !');
        
        $form = $this->createForm(new SecteursMembreType(), $membre);
        
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Vos secteurs ont bien été mis à jour.');
                return $this->redirect($this->generateUrl('cec_membre_reglages_secteurs'));
            }
        }
        
        return array('form' => $form->createView());
    }
}
