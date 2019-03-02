<?php

namespace Stage\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FederationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('sigle')
            ->add('creation', 'Symfony\Component\Form\Extension\Core\Type\BirthdayType')
            ->add('president')
            ->add('imageFile', VichImageType::class,array(
                'required' => 'false'))
            ->add('nomPays');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Stage\AdminBundle\Entity\Federation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'stage_adminbundle_federation';
    }


}
