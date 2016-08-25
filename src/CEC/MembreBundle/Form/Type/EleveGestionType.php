<?php

namespace CEC\MembreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EleveGestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('charteEleveRendue', 'checkbox', array(
                'required'=> false
            ))
            ->add('autorisationParentaleRendue', 'checkbox', array(
                'required'=> false
            ))
            ->add('droitImageRendue', 'checkbox', array(
                'required'=> false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Eleve'
        ));
    }

    public function getName()
    {
        return 'cec_membrebundle_elevegestiontype';
    }
}
