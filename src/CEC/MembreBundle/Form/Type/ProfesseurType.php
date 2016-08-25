<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', 'text', array(
                'label' => 'Prénom',
                'attr' => array('autofocus' => '1', 'placeholder'=>'Prénom'),
            ))
            ->add('nom', 'text', array(
                'label'=>'Nom',
                'attr' => array('placeholder' => 'Nom'),
            ))
            ->add('mail', 'text', array(
                'label' => 'Adresse email',
                'attr' => array('placeholder' => 'Adresse Mail'),
            ))
            ->add('telephoneFixe', 'text', array(
                'label' => 'Numéro de téléphone fixe',
                'required' => false,
            ))
            ->add('telephonePortable', 'text', array(
                'label' => 'Numéro de téléphone portable',
                'required' => false,
            ))
            ->add('lycee', null, array(
                'label'=>'Lycée de provenance',
            ))
            ->add('role', 'choice', array(
                'choices' => array ('proviseur' => "Proviseur", "proviseurAdjoint" => "Proviseur Adjoint", "cpe" => "Conseiller Principal d'Education", "professeur" => "Enseignant"),
                'label' => 'Rôle dans l\'établissement'
            ))
            ->add('motDePasse', 'repeated', array(
                'label'=>'Mot de passe',
                'first_name' => 'Mot-de-passe',
                'second_name' => 'Confirmation',
                'type' => 'password',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Professeur'
        ));
    }

    public function getName()
    {
        return 'cec_membrebundle_professeurtype';
    }
}
