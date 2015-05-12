<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\MembreBundle\Form\Type\InfosEleveType;
use CEC\MembreBundle\Form\Type\MotDePasseMembreType;
use CEC\MembreBundle\Form\Type\GroupeEleveType;

use CEC\TutoratBundle\Entity\GroupeEleves;

use CEC\MainBundle\Utility\Referer;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;



class ReglagesEleveController extends Controller
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
        
        $infomationsGenerales = $this->createForm(new InfosEleveType(), $membre);
            
        $motDePasse = $this->createForm(new MotDePasseMembreType());
        
        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            if ($request->request->has($nomInformationsGenerales)) {
                $infomationsGenerales->bindRequest($request);
                if ($infomationsGenerales->isValid()) {
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->get('session')->setFlash('success', 'Les modifications ont bien été enregistrées.');
                    return $this->redirect($this->generateUrl('reglages_infos_eleve'));
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
					return $this->redirect($this->generateUrl('reglages_infos_eleve'));
                }
            }
        }
        
        return array(
            'informations_generales' => $infomationsGenerales->createView(),
            'mot_de_passe'           => $motDePasse->createView(),
            'lyceen'                 => $membre
        );
    }
    
    /**
     * Sélection de son groupe de tutorat régulier.
     * @Template()
     */
    public function groupeAction()
    {
        $lyceen = $this->getUser();

        $form = $this->createForm(new GroupeEleveType(), $lyceen);

        $data = $this->getRequest()->get($form->getName());
        if($data != null)
        {
            if (array_key_exists('groupe', $data))
            {
                $groupe = $data['groupe'];
            } else {
                $this->get('session')->setFlash('error', 'Merci de spécifier un groupe que vous voulez rejoindre.');
                return $this->redirect($this->generateUrl('reglages_groupe_eleve'));
            }

            $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
            if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe !');

            $groupeLyceen = new GroupeEleves();
            $groupeLyceen->setAnneeScolaire(AnneeScolaire::withDate());
            $groupeLyceen->setLyceen($lyceen);
            $groupeLyceen->setGroupe($groupe);

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($groupeLyceen);
            $em->flush();

            $this->get('session')->setFlash('success', 'Votre groupe de tutorat a bien été modifié.');
        }
        
        
        return array('form' => $form->createView(), 'lyceen' => $lyceen );
    }

       
}
