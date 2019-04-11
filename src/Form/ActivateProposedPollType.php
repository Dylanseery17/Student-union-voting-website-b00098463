<?php

namespace App\Form;

use App\Entity\ProposedPoll;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ActivateProposedPollType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Enddate', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'input' => 'string',
                'format' => 'yyyy-MM-dd',
                'data' => date('Y-m-d'),
                'attr' => array(
                    'placeholder' => 'yyyy-MM-dd',
                )
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProposedPoll::class,
        ]);
    }
}
