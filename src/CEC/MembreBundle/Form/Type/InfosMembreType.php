<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InfosMembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('prenom', null, array('label' => 'Prénom'))
            ->add('nom')
            ->add('promotion', null, array('grouping' => false))
			->add('email')
            ->add('telephone', null, array('label' => 'Numéro de téléphone'
            ))
            ->add('checkMail', 'checkbox', array(
                    'label' => 'Recevoir les mails automatiques de CEC',
                    'required' => false
            ));
    }
    
    public function getName() {
        return 'InfosMembre';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Membre',
        ));
    }
}
