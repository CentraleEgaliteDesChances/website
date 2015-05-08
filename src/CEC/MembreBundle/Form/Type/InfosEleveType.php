<?php

namespace CEC\MembreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InfosEleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('prenom', null, array('label' => 'Prénom'))
            ->add('nom', null, array('label' => 'Nom de famille'))
            ->add('groupe', null, array(
				'label' => 'Groupe de tutorat',
				'property' => 'description'))
            ->add('mail', 'text', array(
					'label' => 'Adresse email',
					'attr' => array('placeholder' => 'Adresse Mail'),
			))
            ->add('adresse', null, array('label' => 'Numéro et nom de la rue'))
            ->add('codePostal', null, array('label' => 'Code Postal'))
            ->add('ville', null, array('label' => 'Ville'))
            ->add('telephone', null, array('label' => 'Numéro de téléphone'))
			->add('telephonePublic', 'checkbox', array(
					'label'     => 'Afficher publiquement le numéro de téléphone ?',
					'required'  => false,
			))
			->add('nomPere', null, array('label' => 'Nom du père'))
			->add('nomMere', null, array('label' => 'Nom de la mère'))
			->add('telephoneParent', null, array('label' => 'Téléphone du(es) parent(s)'));
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
