<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('prenom', null, array(
                'label' => 'Prénom',
            ))
            ->add('role')
            ->add('lycee', null, array(
                'label' => 'Lycée associé',
                'empty_value' => 'Aucun lycée',
            ))
            ->add('email', null, array(
                'label' => 'Adresse Email',
            ))
            ->add('telephonePortable', null, array(
                'label' => 'Téléphone portable',
            ))
            ->add('telephoneFixe', null, array(
                'label' => 'Téléphone fixe',
            ));
    }
    
    public function getName()
    {
        return 'enseignant';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Professeur'
        ));
    }
}
