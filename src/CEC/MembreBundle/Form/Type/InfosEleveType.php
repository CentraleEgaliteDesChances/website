<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InfosEleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('prenom', null, array('label' => 'Prénom'))
            ->add('nom')
            ->add('mail', 'text', array(
					'label' => 'Adresse email',
					'attr' => array('placeholder' => 'Adresse Mail'),
			))
			->add('lycee', 'choice', array(
					'label'=>'Lycée de provenance',
					'choices' => array(	'jjmont'=>'Jean Jaurès Montreuil', 'jjchat' => 'Jean Jaurès Châtenay', 'cpb' => 'Charles Péguy Bobigny', 
									'cpp'=>'Charles Péguy Paris', 'matisse'=>'Henri Matisse Montreuil', 'mounier'=>'Emmanuel Mounier Chatenay',
									'monod' => 'Jacques Monod Clamart', 'montesquieu'=>"Montesquieu le Plessis")
			))
			->add('classe', 'choice', array(
					'label'=>'Classe actuelle',
					'choices'=>array('2nde'=>'Seconde','1e' =>'Première', 'Tale'=>'Terminale')
			))
            ->add('telephone', null, array('label' => 'Numéro de téléphone'))
			->add('telephonePublic', 'checkbox', array(
					'label'     => 'Afficher publiquement le numéro de téléphone ?',
					'required'  => false,
			));
    }
    
    public function getName() {
        return 'InfosEleve';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\Eleve',
        ));
    }
}
