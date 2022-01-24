<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticlesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('auteur')
            ->add('titre')
            ->add('periode')
            ->add('numero_parent')
            ->add('href_parent')
            ->add('resume_fr', null, [
                'attr' => ['style' => 'height:200px']
            ])
            ->add('resume_en', null, [
                'attr' => ['style' => 'height:200px']
            ])
            ->add('contenu', null, [
                'attr' => ['style' => 'height:400px']
            ])
            ->add('biblio', null, [
                'attr' => ['style' => 'height:200']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
