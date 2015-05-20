<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\MembreBundle\Form\Type\InfosMembreType;
use CEC\MembreBundle\Form\Type\MotDePasseMembreType;
use CEC\MembreBundle\Form\Type\SecteursMembreType;
use CEC\MembreBundle\Form\Type\GroupeMembreType;

use CEC\MembreBundle\Entity\Secteur;

use CEC\TutoratBundle\Entity\GroupeTuteurs;
use CEC\TutoratBundle\Entity\groupeEleves;
use CEC\MainBundle\AnneeScolaire\AnneeScolaire;

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
                    return $this->redirect($this->generateUrl('reglages_infos'));
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
					return $this->redirect($this->generateUrl('reglages_infos'));
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

        $data = $this->getRequest()->get($form->getName());
        if($data!= null)
        {
            if (array_key_exists('groupe', $data))
            {
                $groupe = $data['groupe'];
            } else {
                $this->get('session')->setFlash('error', 'Merci de spécifier un groupe que vous voulez rejoindre.');
                return $this->redirect($this->generateUrl('reglages_groupe'));
            }
            $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
            if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe !');

            $groupeTuteur = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeTuteurs')->findOneBy(array('tuteur'=>$membre, 'anneeScolaire' => AnneeScolaire::withDate()));

            $em = $this->getDoctrine()->getEntityManager();

            if(!$groupeTuteur)
            {
                $groupeMembre = new GroupeTuteurs();
                $groupeMembre->setAnneeScolaire(AnneeScolaire::withDate());
                $groupeMembre->setTuteur($membre);
                $groupeMembre->setGroupe($groupe);

                $em->persist($groupeMembre);
                $this->get('session')->setFlash('success', 'Vous avez bien été affecté au groupe de tutorat.');
            }
            else
            {
                $groupeTuteur->setGroupe($groupe);
                $this->get('session')->setFlash('success', 'Votre groupe de tutorat a bien été modifié.');
            }

            $em->flush();

            
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
                $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->refreshUser($membre);
                $membre->updateRoles();
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Vos secteurs ont bien été mis à jour. Une déconnexion est nécessaire pour voir les modifications de vos accès.');
                return $this->redirect($this->generateUrl('reglages_secteurs'));
            }
        }
        
        return array('form' => $form->createView());
    }

    /** 
    * Ajout de secteurs
    *
    * @Template()
    */
    public function creerSecteurAction()
    {
        $secteurs = $this->getDoctrine()->getRepository('CECMembreBundle:Secteur')->findAll();

        $secteur = new Secteur();
        $form = $this->createFormBuilder($secteur)
                        ->add('nom', 'text', array('label' => 'Nom du nouveau secteur'))
                    ->getForm();

        $request = $this->getRequest();

        if($request->isMethod('POST'))
        {
            $form->bindRequest($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Le secteur a bien été créé');

                return $this->redirect($this->generateUrl('creer_secteur'));
            }
        }

        return array('form' => $form->createView(), 'secteurs' => $secteurs);
    }

    /**
    * Suppression de secteur
    *
    * @param integer $secteur : id du secteur à supprimer
    * 
    * @Template()
    */
    public function supprimerSecteurAction($secteur)
    {
        $secteur = $this->getDoctrine()->getRepository('CECMembreBundle:Secteur')->find($secteur);
        if(!$secteur) throw $this->createNotFoundException('Le secteur demandé n\'a pas été trouvé !');

        $membres = $this->getDoctrine()->getRepository('CECMembreBundle:Membres')->findAll();

        // On retire les droits du secteur de chaque membre qui y était
        foreach($membres as $membre)
        {
            if(in_array($secteur, $secteurs))
            {
                $membre->removeSecteur($secteur);
                $membre->updateRoles();
            }
        }

        $em = $this->getDoctrine()->getEntityManager();

        $em->remove($secteur);
        $em->flush();

        $this->get('session')->setFlash('success', 'Le secteur a bien été supprimé et les droits des membres y appartenant ont été modifiés');
        return $this->redirect($this->generateUrl('creer_secteur'));
    }
}
