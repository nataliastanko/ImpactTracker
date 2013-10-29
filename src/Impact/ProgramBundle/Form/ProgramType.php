<?php

namespace Impact\ProgramBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class ProgramType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {//Personal Economics. For ages 12-16
        $builder
            ->add('title')
            ->add('description')
            ->add('participants', 'entity', array(
                'class' => 'ImpactUserBundle:User', 
                'query_builder' => function(EntityRepository $er) /*use ($options)*/ {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                    //return $er->getSpecificToForm();
                },
                'required'  => false,
                'multiple' => true,
                'property' => 'fullname',
                'label' => 'Participants',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Impact\ProgramBundle\Entity\Program'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'impact_programbundle_program';
    }
}
