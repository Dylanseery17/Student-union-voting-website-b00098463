<?php

namespace App\Form;

use App\Entity\ProposedPoll;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProposedPollType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name')
            ->add('Upload_Image', FileType::class, [
                'mapped' => false,
                'label' => 'Please upload an image',
                'multiple' => true,
            ])
            ->add('Options', null, [

            ])
            ->add('Description', TextareaType::class, array(
                'attr' => array('cols' => '5', 'rows' => '5'),
            ));
        ;
        $builder->get('Options')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(', ', $tagsAsString);
                }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProposedPoll::class,
        ]);
    }
}
