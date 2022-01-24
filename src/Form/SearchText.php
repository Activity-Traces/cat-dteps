<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class SearchText extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType::class, ['label' => false,  'required' => false])

            ->add(
                'rechercher',
                ChoiceType::class,
                array(
                    'choices' => array(
                        'Mot' => '1',
                        'Sequence' => '2'
                    ),
                    'multiple' => false, 'expanded' => true,
                    'data' => '1'

                )
            )

            ->add('canDictio', CheckboxType::class, [
                'label'    => 'Mise à jour automatique du dictionnaire local',
                'required' => false
            ])


            ->add('LenguageIn', ChoiceType::class, [
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
            ->add('LenguageOut', ChoiceType::class, [
                'choices'  => [
                    'en' => 'en',
                    'fr' => 'fr',
                    'it' => 'it',
                    'ar' => 'ar',
                    'de' => 'de',
                    'es' => 'es',
                    'ru' => 'ru',

                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
