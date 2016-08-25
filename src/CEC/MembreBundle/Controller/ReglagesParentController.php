<?php
/**
 * Created by PhpStorm.
 * User: eung
 * Date: 18/05/16
 * Time: 14:37
 */

namespace CEC\MembreBundle\Controller;


use CEC\MembreBundle\Form\Type\EnfantsParentType;
use CEC\MembreBundle\Form\Type\InfosParentType;
use CEC\MembreBundle\Form\Type\MotDePasseMembreType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReglagesParentController extends Controller
{
    /**
     * Modification des informations personnelles.
     * Cette page permet de modifier les informations personnelles d'un parent (nom, prénom,
     * adresse électronique, numéro de téléphone). Elle permet aussi de changer le mot de passe.
     *
     * @Template()
     */
    public function infosAction()
    {
        // On récupère l'utilisateur actuel
        $parent = $this->getUser();

        $nomInformationsGenerales = 'InfosParent';
        $infomationsGenerales = $this->get('form.factory')
            ->createNamedBuilder($nomInformationsGenerales, new InfosParentType(), $parent)
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
                    return $this->redirect($this->generateUrl('reglages_infos_parent'));
                }
            }

            if ($request->request->has($nomMotDePasse)) {
                $motDePasse->handleRequest($request);
                if ($motDePasse->isValid()) {
                    $data = $motDePasse->getData();
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($parent);
                    $ancienMotDePasse = $encoder->encodePassword($data['ancienMotDePasse'], $parent->getSalt());

                    if ($ancienMotDePasse == $parent->getMotDePasse()){
                        $password = $encoder->encodePassword($data['motDePasse'], $parent->getSalt());
                        $parent->setMotDePasse($password);

                        $this->getDoctrine()->getEntityManager()->flush();
                        $this->get('session')->getFlashBag()->add('success', 'Le mot de passe a bien été modifié.');
                    } else {
                        $this->get('session')->getFlashBag()->add('danger', 'Mauvais mot de passe');
                    }
                    return $this->redirect($this->generateUrl('reglages_infos_parent'));
                }
            }
        }

        return array(
            'informations_generales' => $infomationsGenerales->createView(),
            'mot_de_passe'           => $motDePasse->createView(),
            'parent'             => $parent
        );
    }

    /**
     * Cette page permet au parent de modifier ces enfants
     *
     * @Template()
     */
    public function enfantsAction()
    {
        $parent = $this->getUser();
        $form = $this->createForm(EnfantsParentType::class,$parent);

        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                $this->get('session')->getFlashBag()->add('success', 'Les modifications ont bien été enregistrées.');
                return $this->redirect($this->generateUrl('reglages_enfants_parent'));
            }
        }
        return array(
            'parent' => $parent,
            'form' => $form->createView()
        );
    }

}