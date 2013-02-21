<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use CEC\MembreBundle\Entity\Membre;
use CEC\MembreBundle\Entity\Secteur;

use CEC\MembreBundle\Form\Type\InformationsGeneralesType;
use CEC\MembreBundle\Form\Type\MotDePasseType;
use CEC\MembreBundle\Form\Type\ChoixSecteursType;
use CEC\MembreBundle\Form\Type\SecteurType;

class ReglagesController extends Controller
{
    public function indexAction()
    {
        // Redirige vers les réglages du profil
        return $this->redirect($this->generateUrl('reglages_profil'));
    }
    
    public function profilAction(Request $request)
    {
        $membre = $this->get('security.context')->getToken()->getUser();
        
        $nomInformationsGenerales = 'informations_generales';
        $infomationsGenerales = $this->get('form.factory')
            ->createNamedBuilder($nomInformationsGenerales, new InformationsGeneralesType(), $membre)
            ->getForm();
            
        $nomMotDePasse = 'mot_de_passe';
        $motDePasse = $this->get('form.factory')
            ->createNamedBuilder($nomMotDePasse, new MotDePasseType())
            ->getForm();
        
        if ($request->getMethod() == 'POST') {
            if ($request->request->has($nomInformationsGenerales))
            {
                $infomationsGenerales->bindRequest($request);
                if ($infomationsGenerales->isValid()) {
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->get('session')->setFlash('success', 'Les modifications ont bien été enregistrées.');
                    return $this->redirect($this->generateUrl('reglages_profil'));
                }
            }
            
            if ($request->request->has($nomMotDePasse))
            {
                $motDePasse->bindRequest($request);
                if ($motDePasse->isValid()) {
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($membre);
                    $data = $motDePasse->getData();
                    $password = $encoder->encodePassword($data['motDePasse'], $membre->getSalt());
                    $membre->setMotDePasse($password);
                    
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->get('session')->setFlash('success', 'Le mot de passe a bien été modifié.');
                    return $this->redirect($this->generateUrl('reglages_profil'));
                }
            }
        }
        
        return $this->render('CECMembreBundle:Reglages:profil.html.twig', array(
            'informations_generales' => $infomationsGenerales->createView(),
            'mot_de_passe'           => $motDePasse->createView(),
        ));
    }
    
    public function notificationsAction(Request $request)
    {
        $membre = $this->get('security.context')->getToken()->getUser();
        
        $form = $this->createFormBuilder($membre)
            ->add('actif')
            ->getForm();
        
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Votre statut a bien été modifié.');
                return $this->redirect($this->generateUrl('reglages_notifications'));
            }
        }
    
        return $this->render('CECMembreBundle:Reglages:notifications.html.twig', array(
            'form' => $form->createView(),
        ));
    } 
    
    public function groupesDeTutoratAction(Request $request)
    {
        return $this->render('CECMembreBundle:Reglages:tutorat.html.twig');
    }
    
    public function secteursAction(Request $request)
    {
        $membre = $this->get('security.context')->getToken()->getUser();
        
        $nomChoixSecteurs = 'choix_secteurs';
        $choixSecteurs = $this->get('form.factory')
            ->createNamedBuilder($nomChoixSecteurs, new ChoixSecteursType(), $membre)
            ->getForm();
            
        $nomSecteurForm = 'secteur';
        $nouveauSecteur = new Secteur();
        $secteurForm = $this->get('form.factory')
            ->createNamedBuilder($nomSecteurForm, new SecteurType(), $nouveauSecteur)
            ->getForm();
        
        if ($request->getMethod() == 'POST') {
            if ($request->request->has($nomChoixSecteurs))
            {
                $choixSecteurs->bindRequest($request);
                if ($choixSecteurs->isValid()) {
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->get('session')->setFlash('success', 'Vos secteurs ont bien été mis à jour.');
                    return $this->redirect($this->generateUrl('reglages_secteurs'));
                }
            }
            
            if ($request->request->has($nomSecteurForm))
            {
                $secteurForm->bindRequest($request);
                if ($secteurForm->isValid()) {
                    $entityManager = $this->getDoctrine()->getEntityManager();
                    $entityManager->persist($nouveauSecteur);
                    $entityManager->flush();
                    
                    $this->get('session')->setFlash('success', 'Le secteur a bien été ajouté.');
                    return $this->redirect($this->generateUrl('reglages_secteurs'));
                }
            }
        }
        
        return $this->render('CECMembreBundle:Reglages:secteurs.html.twig', array(
          'choix_secteurs' => $choixSecteurs->createView(),
          'secteur'        => $secteurForm->createView(),
          'afficher_modal' => $request->request->has($nomSecteurForm),
        ));
    }
}
