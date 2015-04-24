<?php

namespace CEC\TutoratBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AjouterDelegueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lycee = $this->lycee;

        $builder->add('delegue', 'entity', array(
            'label' => false,
            'class' => 'CECMembreBundle:Eleve',
            'query_builder' => function (EntityRepository $entityRepository) use($lycee)
            {
                return $entityRepository->createQueryBuilder('e')
                    ->where('e.lycee = :lycee')
                    ->andWhere('e.delegue IS NULL')
                    ->orderBy('e.nom', 'ASC')
                    ->setParameter('lycee', $lycee);
            },
            'empty_value' => false,
            'attr' => array('class' => 'input-ajouter'),
        ));
    }
    
    public function getName()
    {
        return 'ajouter_delegue';
    }

    public function __construct (\CEC\TutoratBundle\Entity\Lycee $lycee)
{
    $this->lycee =$lycee;
}
}
