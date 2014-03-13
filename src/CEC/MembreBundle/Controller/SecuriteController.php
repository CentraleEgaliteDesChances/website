<?php

namespace CEC\MembreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

use CEC\MembreBundle\Form\Type\UserNameMembreType;

class SecuriteController extends Controller
{
    /**
     * Connexion au site interne.
     * Cette page est appelé automatiquement par le composant Security de Symfony.
     * Elle présente un formulaire permettant d'entrer un identifiant (prénom + nom) ainsi
     * qu'un mot de passe ; un bouton connexion lance la procédure d'authentification.
     *
     * @Route("/connexion")
     * @Template()
     */
    public function connexionAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        
        // Récupère les erreurs de connexion si nécessaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $erreur = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $erreur = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return array(
            // Dernier nom d'utilisateur entré
            'dernier_utilisateur'    => $session->get(SecurityContext::LAST_USERNAME),
            'erreur'                 => $erreur,
        );
    }

    /**
     * Gestion des mots de passes oubliés.
     * La page appelée contient un formulaire afin de récupérer le userName
     * du membre concerné.
     * Permet de réinitialiser le mot de passe d'un membre,
     * et de lui envoyer ses identifiants par mail.
     *
     * @Route("/connexion/oubli")
     * @Template()
     */
    public function oubliAction()
    {
        // On récupère l'utilisateur actuel        
            
        $nomUserName = 'UserNameMembre';
        $userName = $this->get('form.factory')
            ->createNamedBuilder($nomUserName, new UserNameMembreType())
            ->getForm();
        
        $request = $this->getRequest();
        if ($request->isMethod("POST"))
        {            
            if ($request->request->has($nomUserName)) 
            {
                $userName->bindRequest($request);
                $data = $userName->getData();

                $membre = $this->getDoctrine()->getRepository('CECMembreBundle:Membre')->loadUserByUsername($data['prenom'] . ' ' . $data['nom']); //$data['prenom'] + ' ' + $data['nom']);

                $motDePasse = substr(str_shuffle(MD5(microtime())), 0, 10);

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($membre);
                $password = $encoder->encodePassword($motDePasse, $membre->getSalt());
                $membre->setMotDePasse($password);

                $entityManager = $this->getDoctrine()->getEntityManager();
                $entityManager->persist($membre);
                $entityManager->flush();
                
                /* Envoyer un message // (quand le développement sera fini)
                $email = \Swift_Message::newInstance()
                    ->setSubject("Mot de passe pour le site interne de CEC")
                    ->setFrom(array("notification@cec-ecp.com" => "Notification CEC"))
                    ->setTo(array($membre->getEmail() => $membre->__toString()))
                    ->setBody(
                        $this->renderView('CECMembreBundle:Mail:oubli.html.twig',
                            array(
                                'membre' => $membre,
                                'mot_de_passe' => $motDePasse,
                                'base_url' => $_SERVER['HTTP_HOST'],
                            )),
                        'text/html');
                $this->get('mailer')->send($email); */

                //$this->get('session')->setFlash('success', 'Le mot de passe de ' . $data['prenom'] . ' ' . $data['nom'] . ' a bien été réinitialisé.');
                return $this->redirect($this->generateUrl('cec_membre_securite_connexion'));                
            }
        }
        
        return array(
            'user_name'           => $userName->createView()
        );
    }
}
