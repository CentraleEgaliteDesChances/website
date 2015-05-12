<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\MembreBundle\Form\Type\InfosProfesseurType;
use CEC\MembreBundle\Form\Type\MotDePasseMembreType;
use CEC\MembreBundle\Form\Type\GroupeProfesseurType;

class ReglagesProfesseurController extends Controller
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
        
        $nomInformationsGenerales = 'InfosProfesseur';
        $infomationsGenerales = $this->get('form.factory')
            ->createNamedBuilder($nomInformationsGenerales, new InfosProfesseurType(), $membre)
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
                    // On met à jour les rôles
                    $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Professeur')->refreshUser($membre);
                    $membre->updateRoles();
                    $this->getDoctrine()->getEntityManager()->flush();

                    $this->get('session')->setFlash('success', 'Les modifications ont bien été enregistrées. Une déconnexion peut être nécessaire pour prendre en compte votre nouveau rôle.');
                    return $this->redirect($this->generateUrl('reglages_infos_professeur'));
                }
            }
            
            if ($request->request->has($nomMotDePasse)) {
                $motDePasse->bindRequest($request);
                if ($motDePasse->isValid()) {
					$data = $motDePasse->getData(); 
					$factory = $this->get('security.encoder_factory');
					$encoder = $factory->getEncoder($membre);
					$ancienMotDePasse = $encoder->encodePassword($data['ancienMotDePasse'], $membre->getSalt());
					
					if ($ancienMotDePasse == $membre->getMotDePasse()){
						$password = $encoder->encodePassword($data['motDePasse'], $membre->getSalt());
						$membre->setMotDePasse($password);
						
						$this->getDoctrine()->getEntityManager()->flush();
						$this->get('session')->setFlash('success', 'Le mot de passe a bien été modifié.');
					} else {
						$this->get('session')->setFlash('danger', 'Mauvais mot de passe'); 
					}
					return $this->redirect($this->generateUrl('reglages_infos_professeur'));
                }
            }
        }
        
        return array(
            'informations_generales' => $infomationsGenerales->createView(),
            'mot_de_passe'           => $motDePasse->createView(),
			'professeur'             => $membre
        );
    }
       
}
