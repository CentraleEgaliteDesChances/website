<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InfosProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('prenom', null, array('label' => 'Prénom'))
            ->add('nom')
            ->add('mail', 'text', array(
					'label' => 'Adresse email',
					'attr' => array('placeholder' => 'Adresse Mail'),
			))
			->add('lycee', null , array(
					'label'=>'Lycée',
					'property'=> 'nom'
			))
            ->add('role', 'choice', array(
                'label' => 'Rôle',
                'choices' => array(
                                    'proviseur' => 'Chef d\'établisement',
                                    'proviseurAdjoint' => 'Proviseur Adjoint',
                                    'cpe' => 'Conseiller Principal d\'Education',
                                    'professeur' => 'Enseignant')
            ))
            ->add('telephoneFixe', null, array('label' => 'Numéro de téléphone fixe'))
            ->add('telephonePortable', null, array('label' => 'Numéro de téléphone portable (visible uniquement par les responsables de CEC et vos collègues)'
            ))
            ->add('checkMail', 'checkbox', array(
                    'label' => 'Recevoir les mails automatiques de CEC',
                    'required' => false
            ));
    }
    
    public function getName() {
        return 'InfosProfesseur';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Professeur',
        ));
    }
}
