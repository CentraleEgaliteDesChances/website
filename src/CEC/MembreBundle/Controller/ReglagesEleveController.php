<?php

namespace CEC\MembreBundle\Controller;

use CEC\MembreBundle\Entity\DossierInscription;
use CEC\MembreBundle\Form\Type\DossierInscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CEC\MembreBundle\Form\Type\InfosEleveType;
use CEC\MembreBundle\Form\Type\MotDePasseMembreType;
use CEC\MembreBundle\Form\Type\GroupeEleveType;


use CEC\TutoratBundle\Entity\GroupeEleves;

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

        $nomInformationsGenerales = 'InfosEleve';
        $infomationsGenerales = $this->get('form.factory')
            ->createNamedBuilder($nomInformationsGenerales, new InfosEleveType(), $membre)
            ->getForm();

        $nomMotDePasse = 'MotDePasseMembre';
        $motDePasse = $this->get('form.factory')
            ->createNamedBuilder($nomMotDePasse, new MotDePasseMembreType())
            ->getForm();

        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            if ($request->request->has($nomInformationsGenerales)) {
                $infomationsGenerales->handleRequest($request);
                if ($infomationsGenerales->isValid()) {
                    $this->getDoctrine()->getEntityManager()->flush();
                    $this->get('session')->getFlashBag()->add('success', 'Les modifications ont bien été enregistrées.');
                    return $this->redirect($this->generateUrl('reglages_infos_eleve'));
                }
            }

            if ($request->request->has($nomMotDePasse)) {
                $motDePasse->handleRequest($request);
                if ($motDePasse->isValid()) {
                    $data = $motDePasse->getData();
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($membre);
                    $ancienMotDePasse = $encoder->encodePassword($data['ancienMotDePasse'], $membre->getSalt());

                    if ($ancienMotDePasse == $membre->getMotDePasse()){
                        $password = $encoder->encodePassword($data['motDePasse'], $membre->getSalt());
                        $membre->setMotDePasse($password);

                        $this->getDoctrine()->getEntityManager()->flush();
                        $this->get('session')->getFlashBag()->add('success', 'Le mot de passe a bien été modifié.');
                    } else {
                        $this->get('session')->getFlashBag()->add('danger', 'Mauvais mot de passe');
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
                $this->get('session')->getFlashBag()->add('error', 'Merci de spécifier un groupe que vous voulez rejoindre.');
                return $this->redirect($this->generateUrl('reglages_groupe_eleve'));
            }

            $groupe = $this->getDoctrine()->getRepository('CECTutoratBundle:Groupe')->find($groupe);
            if (!$groupe) throw $this->createNotFoundException('Impossible de trouver le groupe !');

            $groupeEleve = $this->getDoctrine()->getRepository('CECTutoratBundle:GroupeEleves')->findOneBy(array('lyceen'=>$lyceen, 'anneeScolaire' => AnneeScolaire::withDate()));

            $em = $this->getDoctrine()->getEntityManager();

            if(!$groupeEleve)
            {
                $groupeMembre = new GroupeEleves();
                $groupeMembre->setAnneeScolaire(AnneeScolaire::withDate());
                $groupeMembre->setLyceen($lyceen);
                $groupeMembre->setGroupe($groupe);

                $em->persist($groupeMembre);
            }
            else
            {
                $groupeEleve->setGroupe($groupe);
            }

            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Votre groupe de tutorat a bien été modifié.');
        }


        return array('form' => $form->createView(), 'lyceen' => $lyceen );
    }


    /**
     * Sélection de son groupe de tutorat régulier.
     * @Template()
     */
    public function dossierInscriptionAction()
    {  // On récupère l'utilisateur actuel
        $lyceen = $this->getUser();
        $dossierInscription = $lyceen->getDossierInscription();
        if ($dossierInscription == null){
            $dossierInscription = new DossierInscription();
        }
        $form = $this->createForm(new DossierInscriptionType(),$dossierInscription);
        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $lyceen->setDossierInscription($dossierInscription);
                $mailparent = $form->get('mailParent')->getData();

                $this->getDoctrine()->getEntityManager()->persist($lyceen);
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->getFlashBag()->add('success', 'Les modifications ont bien été enregistrées.');
                $this->get('cec.mailer')->sendNotificationParentInscriptionEleve($mailparent, $lyceen, $_SERVER['HTTP_HOST']);
                return $this->redirect($this->generateUrl('reglages_dossier_inscription_eleve'));
            }
        }

        return array(
            'form' => $form->createView(),
            'lyceen'=> $lyceen
        );
    }
}
