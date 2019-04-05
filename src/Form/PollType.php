<?php

namespace App\Form;

use App\Entity\Poll;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PollType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dt = new \DateTime();
        $builder
            ->add('Name')
            ->add('Upload_Image', FileType::class, [
                'mapped' => false,
                'label' => 'Please upload an image',
                'multiple' => true,
            ])
            ->add('Options', null, [

            ])
            ->add('Description')
            ->add('enddate', DateType::class, [
                'placeholder' => [new \DateTime()]
            ])
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
            'data_class' => Poll::class,
        ]);
    }
}
