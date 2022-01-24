<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DictionaryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('word', TextType::class, ['label' => 'Mot',  'required' => true])


            ->add('lang', ChoiceType::class, [
                'choices'  => [
                    'fr' => 'fr',
                    'en' => 'en',
                    'it' => 'it',
                    'ar' => 'ar',
                    'de' => 'de',
                    'es' => 'es',
                    'ru' => 'ru',

                ],
            ])
            ->add('langDest', ChoiceType::class, [
                'choices'  => [
                    'fr' => 'fr',
                    'en' => 'en',
                    'it' => 'it',
                    'ar' => 'ar',
                    'de' => 'de',
                    'es' => 'es',
                    'ru' => 'ru',

                ],
            ])
            ->add('translate', TextType::class, ['label' => 'Traduction ',  'required' => true])
            ->add('translated', TextType::class, ['label' => 'Traductions ',  'required' => true])
            ->add('elquals', TextType::class, ['label' => 'Synonymes ',  'required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'App\Entity\Dictionary']);
    }
}
