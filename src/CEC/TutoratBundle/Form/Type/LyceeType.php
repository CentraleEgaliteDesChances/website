<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LyceeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('pivot', 'choice', array(
                'label'     => false,
                'expanded'  => true,
                'choices'   => array('0' => 'Lycée Source', '1' => 'Lycée Pivot'),
            ))
            ->add('cordee', null, array(
                'label'       => 'Cordée',
                'empty_value' => 'Aucune cordée',
            ))
            ->add('adresse', null, array(
                'label' => 'Adresse postale',
            ))
            ->add('codePostal', null, array(
                'label' => 'Code postal',
            ))
            ->add('ville')
            ->add('statut', 'choice', array(
                'label'     => false,
                'expanded'  => true,
                'choices'   => array('Établissement Public' => 'Établissement Public', 
                                     'Établissement Privé'  => 'Établissement Privé'),
            ))
            ->add('telephone', null, array(
                'label' => 'Numéro de téléphone',
            ))
            ->add('zep', 'checkbox', array(
                'label'  => false,
                'required' => false,
                'help_inline'    => 'Ce lycée est situé dans une ZEP',
            ))
            ->add('vpLycees', null, array(
                'label'       => 'VP Lycées',
                'empty_value' => 'Aucun VP Lycée',
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\TutoratBundle\Entity\Lycee'
        ));
    }

    public function getName()
    {
        return 'lycee';
    }
}
