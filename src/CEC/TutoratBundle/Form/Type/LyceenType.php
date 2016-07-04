<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LyceenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prenom', null, array(
            'label' => 'Prénom',
            ))
            ->add('nom')
            ->add('groupe', null, array(
                'label' => 'Groupe de tutorat',
                'empty_value' => 'Aucun groupe de tutorat',
            ))
            ->add('telephone', null, array(
                'label' => 'Numéro de téléphone',
            ))
            ->add('email', null, array(
                'label' => 'Adresse Email',
            ))
            ->add('adresse', null, array(
                'label' => 'Adresse postale',
            ))
            ->add('codePostal', null, array(
                'label' => 'Code postal',
            ))
            ->add('ville');
    }
    
    public function getName()
    {
        return 'lyceen';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Eleve',
        ));
    }
}
