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
			->add('lycee', 'choice', array(
					'label'=>'Lycée',
					'choices' => array(	'jjmont'=>'Jean Jaurès Montreuil', 'jjchat' => 'Jean Jaurès Châtenay', 'cpb' => 'Charles Péguy Bobigny', 
									'cpp'=>'Charles Péguy Paris', 'matisse'=>'Henri Matisse Montreuil', 'mounier'=>'Emmanuel Mounier Chatenay',
									'monod' => 'Jacques Monod Clamart', 'montesquieu'=>"Montesquieu le Plessis")
			))
            ->add('telephone', null, array('label' => 'Numéro de téléphone'))
			;
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
