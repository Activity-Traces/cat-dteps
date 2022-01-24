<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class findCorpus extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $params = $options['data'];
        $param_user = $params['user'];

        $builder
            ->add('word', TextType::class, ['label' => 'Mots clés', 'required' => false])


            ->add('min', NumberType::class, ['label' => 'Fréquence Min', 'required' => false])


            ->add('max', NumberType::class, ['label' => 'Fréquence Max', 'required' => false])


            ->add('clean', CheckboxType::class, ['label' => 'Prétraitement des caractères spécieux', 'required' => false])

            ->add('tab', NumberType::class, ['label' => 'Afficher par', 'required' => false])

            ->add('analyse', SubmitType::class, ['label' => 'Trouver'])

            ->add(
                'choix',
                ChoiceType::class,
                [
                    'choices'  => [

                        'Mots clés' => 'Mots',
                        'Fréquence' => 'Frequence'
                    ],
                    'label' => 'Par', 'multiple' => false, 'expanded' => true, 'required' => true, 'data' => 'Mots'

                ]
            )

            ->add(
                'sorted',
                ChoiceType::class,
                [
                    'choices'  => [

                        'Default' => 'default',
                        'Ordre Alphabetique' => 'defaultdown',
                        'Fréquence croissante' => 'up',
                        'Fréquence décroissante' => 'down'



                    ],
                    'label' => 'Mode de recherche par ', 'multiple' => false, 'expanded' => true, 'required' => true
                ]
            );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
