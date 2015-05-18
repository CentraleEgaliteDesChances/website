<?php

namespace CEC\SecteurSortiesBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CEC\SecteurSortiesBundle\Form\Type\SortieType;

class SansCRSortieType extends SortieType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('nbTuteurs')
                ->remove('commentaire')
                ->remove('prix')
        ;
    }

    public function getName()
    {
        return 'CRSortie';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CEC\SecteurSortiesBundle\Entity\Sortie',
        ));
    }
}
