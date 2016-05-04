<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EleveType extends AbstractType
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
                'attr' => array('placeholder' =>'Nom'),
            ))
            ->add('datenaiss', null, array(
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array('placeholder' => 'JJ/MM/AAAA'),
            ))
            ->add('mail', 'text', array(
                'label' => 'Adresse email',
                'attr' => array('placeholder' => 'Adresse Mail'),
            ))
            ->add('telephone', null, array('label' => 'Numéro de téléphone personnel', 'required' => false
            ))
            ->add('telephoneParent', null, array('label'=>'Numéro de téléphone du(des) parent(s)'
            ))
            ->add('nomPere', null, array('label' => 'Nom du père (optionnel)', 'required' => false, 'attr' => array('placeholder' => 'Prénom Nom')
            ))
            ->add('nomMere', null, array('label' => 'Nom de la mère (optionnel)', 'required' => false, 'attr' => array('placeholder' => 'Prénom Nom')
            ))
            ->add('adresse', null, array('label' => 'Adresse du domicile'
            ))
            ->add('codePostal', null, array('label' => 'Code Postal'
            ))
            ->add('ville', null, array('label' => 'Ville'
            ))
            ->add('lycee', null, array(
                'label'=>'Lycée de provenance',
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
            'data_class' => 'CEC\MembreBundle\Entity\Eleve'
        ));
    }

    public function getName()
    {
        return 'cec_membrebundle_elevetype';
    }
}
