<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use CEC\MembreBundle\Entity\Membre;
use CEC\MembreBundle\Entity\Secteur;

use CEC\MembreBundle\Form\Type\InformationsGeneralesType;
use CEC\MembreBundle\Form\Type\MotDePasseType;
use CEC\MembreBundle\Form\Type\ChoixSecteursType;
use CEC\MembreBundle\Form\Type\ReglagesGroupeType;

class ReglagesController extends Controller
{
    public function indexAction()
    {
        // Redirige vers les réglages du profil
        return $this->redirect($this->generateUrl('reglages_profil'));
    }
    
    public function profilAction(Request $request)
    {
        // On récupère l'utilisateur
        $membre = $this->getUser();
        if (!$membre) throw $this->createNotFoundException('L\'utilisateur actif n\'a pas pu être trouvé !');
        
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
        // On récupère l'utilisateur
        $membre = $this->getUser();
        if (!$membre) throw $this->createNotFoundException('L\'utilisateur actif n\'a pas pu être trouvé !');
        
        $form = $this->createFormBuilder($membre)
            ->add('actif', null, array(
                'help_label' => "Les membres arrêtent d'être actif lorsqu'ils partent en S8, en césure ou en double-diplôme. Ils cessent d'être répertoriés comme tuteurs, ne font plus partie des secteurs et ne reçoivent plus aucune notification.",
            ))
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
        $membre = $this->getUser();
        $form = $this->createForm(new ReglagesGroupeType(), $membre);
        
        if ($request->isMethod("POST")) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Votre groupe de tutorat a bien été modifié.');
                return $this->redirect($this->generateUrl('reglages_groupes_de_tutorat'));
            }
        }
        
        return $this->render('CECMembreBundle:Reglages:tutorat.html.twig', array('form' => $form->createView()));
    }
    
    public function secteursAction(Request $request)
    {
        // On récupère l'utilisateur
        $membre = $this->getUser();
        if (!$membre) throw $this->createNotFoundException('L\'utilisateur actif n\'a pas pu être trouvé !');
        
        $nomChoixSecteurs = 'choix_secteurs';
        $form = $this->createForm(new ChoixSecteursType(), $membre);
        
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->setFlash('success', 'Vos secteurs ont bien été mis à jour.');
                return $this->redirect($this->generateUrl('reglages_secteurs'));
            }
        }
        
        return $this->render('CECMembreBundle:Reglages:secteurs.html.twig', array(
          'form' => $form->createView(),
        ));
    }
}
