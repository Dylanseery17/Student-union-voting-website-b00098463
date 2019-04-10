<?php

namespace App\Form;

use App\Entity\ProposedPoll;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProposedSupport extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProposedPoll::class,
        ]);
    }
}
