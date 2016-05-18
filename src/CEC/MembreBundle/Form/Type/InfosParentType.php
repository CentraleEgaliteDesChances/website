<?php
/**
 * Created by PhpStorm.
 * User: eung
 * Date: 18/05/16
 * Time: 14:39
 *
 * Genere le formulaire de modification de profil d'un parent
 */

namespace CEC\MembreBundle\Form\Type;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InfosParentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('prenom', 'text', array(
                'label' => 'Prénom',
                'attr' => array('autofocus' => '1', 'placeholder'=>'Prénom'),
            ))
            ->add('nom', 'text', array(
                'label'=>'Nom',
                'attr' => array('placeholder' => 'Nom'),
            ))
            ->add('mail', 'text', array(
                'label' => 'Adresse email',
                'attr' => array('placeholder' => 'Adresse Mail'),
            ))
            ->add('telephone', 'text', array(
                'label' => 'Numéro de téléphone portable',
                'required' => false,
            ))
            ->add('eleves', 'entity', array(
                'class' => 'CEC\MembreBundle\Entity\Eleve',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('eleve')
                        ->orderBy('eleve.nom')
                        ->addOrderBy('eleve.prenom');
                },
                'multiple' => true,
                'placeholder' => 'Choisir parmi les élèves inscrits',
                'label' => 'De quels élèves êtes-vous le parent ?'
            ))
            ->add('checkMail', 'checkbox', array(
                'label' => 'Recevoir les mails automatiques de CEC',
                'required' => false
            ));
    }

    public function getName() {
        return 'InfosParent';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\MembreBundle\Entity\ParentEleve',
        ));
    }
}
