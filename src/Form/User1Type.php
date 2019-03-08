<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('roles')
            ->add('password')
            ->add('Firstname')
            ->add('Lastname')
            ->add('Age')
            ->add('StudentNumber')
            ->add('Email')
            ->add('Telephone')
            ->add('Addressline')
            ->add('Addresslineone')
            ->add('City')
            ->add('County')
            ->add('Datecreated')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
